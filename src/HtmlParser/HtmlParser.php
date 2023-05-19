<?php

namespace Hashbangcode\SitemapChecker\HtmlParser;

use Hashbangcode\SitemapChecker\Url\Link;
use Hashbangcode\SitemapChecker\Url\Url;
use Hashbangcode\SitemapChecker\Url\UrlCollection;
use Hashbangcode\SitemapChecker\Url\UrlCollectionInterface;

class HtmlParser implements HtmlParserInterface
{

  /**
   * @param string $data
   * @param Url $url
   * @return UrlCollectionInterface
   */
  public function extractLinksAsUrls(string $data, Url $url): UrlCollectionInterface
  {
    $linkCollection = new UrlCollection();

    $links = $this->extractLinks($data, $url->getRawUrl());

    foreach ($links as $link) {
      if (isset($link['url']) && isset($link['text'])) {
        $foundUrl = new Link($link['url']);
        $foundUrl->setText($link['text']);
        $linkCollection->add($foundUrl);
      }
    }

    return $linkCollection;
  }

  /**
   * Extract links from a given block of HTML.
   *
   * @param string $data
   *   The data to parse.
   * @param string $rootUrl
   *   The URL to associate with the links found.
   *
   * @return array['url' => string, 'link' => string]
   *   The list of found links.
   */
  public function extractLinks(string $data, string $rootUrl):array {
//    * @return array['url' => string, 'link' => string]
    // The base URL.
    $baseUrl = '';

    $parsedRootUrl = parse_url($rootUrl);
    if (isset($parsedRootUrl['scheme']) && isset($parsedRootUrl['host'])) {
      $baseUrl = $parsedRootUrl['scheme'] . '://' . $parsedRootUrl['host'];
    }

    // The split path of the URL.
    $splitPath = [];

    if (isset($parsedRootUrl['path'])) {
      $splitPath = array_filter(explode('/', $parsedRootUrl['path']));
    }

    // The links extracted from the data.
    $linkArray = [];

    if (preg_match_all('/<a\s+.*?href=[\"\']?([^\"\' >]*)[\"\']?[^>]*>(.*?)<\/a>/i', $data, $matches, PREG_SET_ORDER)) {
      foreach ($matches as $match) {
        if (strlen($match[1]) > 0 && $match[1][0] != '#') {
            $foundUrl = $match[1];
            $foundText = $match[2];

            // Might be non-http location
            $testMatch = parse_url($foundUrl);
            if (isset($testMatch['scheme']) && str_starts_with($testMatch['scheme'], 'http') === false) {
              // Don't include anything that isn't HTTP based.
              continue;
            }

            // Figure out counts for the number of back markers and the number of slashes.
            $backCount = substr_count($foundUrl, '../');
            preg_match('/(?:\/)?([^\.]+\/)*(?:\/)?/', $foundUrl, $foundSlashes);
            $slashesCount = 0;
            if (isset($foundSlashes[1])) {
              $slashesCount = substr_count($foundSlashes[1], '/');
            }

            if ($backCount > 0) {
              // If the back count is set then figure out the relative path.
              $tmpSplitPath = $splitPath;
              $length = count($tmpSplitPath) - $backCount;
              $length -= $slashesCount;
              $path = array_splice($tmpSplitPath, 0, $length);
              $foundUrl .= '/' . implode('/', $path);
              $foundUrl = str_replace(['../', './'], '', $foundUrl);
              $foundUrl = str_replace('//', '/', $foundUrl);
              $foundUrl = $baseUrl . $foundUrl;
            }

          // Strip off any # parameters from the end of the URL
          if (strpos($foundUrl, '#') > 1) {
            $foundUrl = substr($foundUrl, 0, strpos($foundUrl, '#'));
          }
          if (str_starts_with($foundUrl, '/')) {
            // Final check to ensure that any relative URLs are absolute.
            $foundUrl = $baseUrl . $foundUrl;
          }
          $linkArray[] = [
            'url' => trim($foundUrl),
            'text' => $foundText,
          ];
        }
      }
    }
    return $linkArray;
  }

  /**
   * Extract the title from the HTML page.
   *
   * @param string $data
   *   The HTML data.
   *
   * @return string
   *   The extracted title, or a blank string if no title was found.
   */
  public function extractTitle($data) {
    if (preg_match('/\<title\>(.*?)\<\/title\>/i', $data, $matches) === 1) {
      return $matches[1];
    }
    return '';
  }
}
