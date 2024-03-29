<?php

namespace Hashbangcode\SitemapChecker\Crawler;

use Hashbangcode\SitemapChecker\HtmlParser\HtmlParser;
use HeadlessChromium\Browser\ProcessAwareBrowser;
use Hashbangcode\SitemapChecker\Result\Result;
use Hashbangcode\SitemapChecker\Result\ResultInterface;
use Hashbangcode\SitemapChecker\Url\UrlInterface;

class ChromeCrawler extends CrawlerBase
{
    public function processUrl(UrlInterface $url): ResultInterface
    {
        $result = new Result($url);

        $htmlParser = new HtmlParser();

        $browser = $this->getEngine();
        if ($browser instanceof ProcessAwareBrowser) {
            $page = $browser->createPage();
            $options = $this->getOptions();

            $headers = $options->getHeaders();
            $headers['Authorization'] = $options->getAuthorization();
            array_filter($headers);
            if (count($headers) !== 0) {
              $page->setExtraHTTPHeaders($headers);
            }

            $page->getSession()->on("method:Network.responseReceived", function (array $params) use ($result): void {
                $result->setResponseCode($params['response']['status']);
                foreach ($params['response']['headers'] as $id => $header) {
                  $params['response']['headers'][$id] = [$header];
                }
                $result->setHeaders($params['response']['headers']);
            });
            $page->navigate($url->getRawUrl())->waitForNavigation();
            $value = $page->evaluate('document.documentElement.outerHTML')->getReturnValue();
            if (is_string($value)) {
              $result->setTitle($htmlParser->extractTitle($value));
              $result->setPageSize(mb_strlen($value));
              $result->setBody($value);
            }
        }

        return $result;
    }

}
