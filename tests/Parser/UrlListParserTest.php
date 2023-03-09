<?php

namespace Hashbangcode\SitemapChecker\Test\Parser;

use Hashbangcode\SitemapChecker\Parser\UrlListParser;
use PHPUnit\Framework\TestCase;

class UrlListParserTest extends TestCase {

  public function testUrlListParserParsesData()
  {
    $urlList = realpath(__DIR__ . '/../data/urllist.txt');
    $urlList = file_get_contents($urlList);

    $urlListParser = new UrlListParser();
    $list = $urlListParser->parse($urlList);

    $this->assertEquals(2, $list->count());

    $this->assertEquals('https', $list->current()->getScheme());
    $this->assertEquals('www.example.com', $list->current()->getHost());
    $this->assertEquals('/', $list->current()->getPath());

    $list->next();

    $this->assertEquals('https', $list->current()->getScheme());
    $this->assertEquals('www.example.com', $list->current()->getHost());
    $this->assertEquals('/inner-link', $list->current()->getPath());
  }
}