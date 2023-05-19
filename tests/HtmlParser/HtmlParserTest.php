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

    $this->assertEquals(9, $list->count());

    // <a href="https://www.example.com/">Absolute home</a>
    $this->assertEquals('https', $list->current()->getScheme());
    $this->assertEquals('www.example.com', $list->current()->getHost());
    $this->assertEquals('/', $list->current()->getPath());
    $this->assertEquals('Absolute home', $list->current()->getText());

    $list->next();

    // <a href="http://www.example.com/">Absolute home (http)</a>
    $this->assertEquals('http', $list->current()->getScheme());
    $this->assertEquals('www.example.com', $list->current()->getHost());
    $this->assertEquals('/', $list->current()->getPath());
    $this->assertEquals('Absolute home (http)', $list->current()->getText());

    $list->next();

    // <a href="/">Slash</a>
    $this->assertEquals('https', $list->current()->getScheme());
    $this->assertEquals('www.example.com', $list->current()->getHost());
    $this->assertEquals('/', $list->current()->getPath());
    $this->assertEquals('Slash', $list->current()->getText());

    $list->next();

    // <a href="../../">Home</a>
    $this->assertEquals('https', $list->current()->getScheme());
    $this->assertEquals('www.example.com', $list->current()->getHost());
    $this->assertEquals('/', $list->current()->getPath());
    $this->assertEquals('Home', $list->current()->getText());

    $list->next();

    // <a href="../">Inner</a>
    $this->assertEquals('https', $list->current()->getScheme());
    $this->assertEquals('www.example.com', $list->current()->getHost());
    $this->assertEquals('/inner', $list->current()->getPath());
    $this->assertEquals('Inner', $list->current()->getText());

    $list->next();

    // <a href="/about">About</a>
    $this->assertEquals('https', $list->current()->getScheme());
    $this->assertEquals('www.example.com', $list->current()->getHost());
    $this->assertEquals('/about', $list->current()->getPath());
    $this->assertEquals('About', $list->current()->getText());

    $list->next();

    // <a href="https://www.example.com/path#fragment">URI fragment 1</a>
    $this->assertEquals('https', $list->current()->getScheme());
    $this->assertEquals('www.example.com', $list->current()->getHost());
    $this->assertEquals('/path', $list->current()->getPath());
    $this->assertEquals('URI fragment 1', $list->current()->getText());

    $list->next();

    // <a href="/"><strong>Strong text</strong></a>
    $this->assertEquals('https', $list->current()->getScheme());
    $this->assertEquals('www.example.com', $list->current()->getHost());
    $this->assertEquals('/', $list->current()->getPath());
    $this->assertEquals('<strong>Strong text</strong>', $list->current()->getText());

    $list->next();

    // <a href="/"><img src="/image.png" alt="Something" /></a>
    $this->assertEquals('https', $list->current()->getScheme());
    $this->assertEquals('www.example.com', $list->current()->getHost());
    $this->assertEquals('/', $list->current()->getPath());
    $this->assertEquals('<img src="/image.png" alt="Something" />', $list->current()->getText());

    $list->next();

    // The list should be empty now.
    $this->assertFalse($list->valid());
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
  public function testExtractSingleLinks($link, $rootUrl, $resultText, $resultUrl) {
    $htmlParser = new HtmlParser();
    $export = $htmlParser->extractLinks($link, $rootUrl);
    $this->assertEquals($resultText, $export[0]['text']);
    $this->assertEquals($resultUrl, $export[0]['url']);
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
      'Home',
      'https://www.example.com/',
    ];

    $links[] = [
      '<a href="../">Home</a>',
      'https://www.example.com/inner/',
      'Home',
      'https://www.example.com/',
    ];

    $links[] = [
      '<a href="../../../">Home</a>',
      'https://www.example.com/inner/path/deep/',
      'Home',
      'https://www.example.com/',
    ];

    $links[] = [
      '<a href="/inner/../">Home</a>',
      'https://www.example.com/inner/path/',
      'Home',
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

  /**
   * Test that parsing a blank title will return an empty string.
   */
  public function testParsingBlankTitleReturnsEmptystring() {
    $htmlParser = new HtmlParser();
    $title = $htmlParser->extractTitle('');
    $this->assertEquals('', $title);

    $htmlParser = new HtmlParser();
    $title = $htmlParser->extractTitle('<title></title>');
    $this->assertEquals('', $title);
  }
}