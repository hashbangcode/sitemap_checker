<?php

namespace Hashbangcode\SitemapChecker\Test\HtmlParser;

use Hashbangcode\SitemapChecker\HtmlParser\HtmlParser;
use Hashbangcode\SitemapChecker\Url\Url;
use PHPUnit\Framework\TestCase;

class HtmlParserTest extends TestCase
{
  public function testHtmlParserExtractsUrlObjectsFromHtml()
  {
    $url = new Url('https://www.example.com/inner/path');

    $htmlPage = realpath(__DIR__ . '/../data/page.html');
    $htmlPage = file_get_contents($htmlPage);

    $htmlParser = new HtmlParser();
    $list = $htmlParser->extractLinksAsUrls($htmlPage, $url);

    $this->assertEquals(6, $list->count());

    // <a href="https://www.example.com/">Absolute home</a>
    $this->assertEquals('https', $list->current()->getScheme());
    $this->assertEquals('www.example.com', $list->current()->getHost());
    $this->assertEquals('/', $list->current()->getPath());

    $list->next();

    // <a href="http://www.example.com/">Absolute home (http)</a>
    $this->assertEquals('http', $list->current()->getScheme());
    $this->assertEquals('www.example.com', $list->current()->getHost());
    $this->assertEquals('/', $list->current()->getPath());

    $list->next();

    // <a href="../../">Home</a>
    $this->assertEquals('https', $list->current()->getScheme());
    $this->assertEquals('www.example.com', $list->current()->getHost());
    $this->assertEquals('/', $list->current()->getPath());

    $list->next();

    // <a href="../">Inner</a>
    $this->assertEquals('https', $list->current()->getScheme());
    $this->assertEquals('www.example.com', $list->current()->getHost());
    $this->assertEquals('/inner', $list->current()->getPath());

    $list->next();

    // <a href="/about">About</a>
    $this->assertEquals('https', $list->current()->getScheme());
    $this->assertEquals('www.example.com', $list->current()->getHost());
    $this->assertEquals('/about', $list->current()->getPath());

    $list->next();

    // <a href="https://www.example.com/path#fragment">URI fragment 1</a>
    $this->assertEquals('https', $list->current()->getScheme());
    $this->assertEquals('www.example.com', $list->current()->getHost());
    $this->assertEquals('/path', $list->current()->getPath());
  }

  /**
   * Test that single links are extracted and translated correctly.
   *
   * @param $link
   * @param $rootUrl
   * @param $result
   *
   * @dataProvider extractSingleLinksProvider
   *
   */
  public function testExtractSingleLinks($link, $rootUrl, $result) {
    $htmlParser = new HtmlParser();
    $export = $htmlParser->extractLinks($link, $rootUrl);
    $this->assertEquals($result, $export[0]);
  }

  /**
   * Data provider for testExtractSingleLinks.
   *
   * @return array
   *   The tests.
   */
  public static function extractSingleLinksProvider() {
    $links = [];

    $links[] = [
      '<a href="/">Home</a>',
      'https://www.example.com/',
      'https://www.example.com/',
    ];

    $links[] = [
      '<a href="../">Home</a>',
      'https://www.example.com/inner/',
      'https://www.example.com/',
    ];

    $links[] = [
      '<a href="../../../">Home</a>',
      'https://www.example.com/inner/path/deep/',
      'https://www.example.com/',
    ];

    $links[] = [
      '<a href="/inner/../">Home</a>',
      'https://www.example.com/inner/path/',
      'https://www.example.com/inner/',
    ];

    return $links;
  }

  /**
   * Test that the page title is extracted correctly.
   */
  public function testHtmlParserExtractsTitle()
  {
    $htmlPage = realpath(__DIR__ . '/../data/page.html');
    $htmlPage = file_get_contents($htmlPage);

    $htmlParser = new HtmlParser();
    $title = $htmlParser->extractTitle($htmlPage);
    $this->assertEquals('HTML 5 Boilerplate', $title);
  }
}