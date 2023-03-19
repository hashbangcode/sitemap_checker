<?php

namespace Hashbangcode\SitemapChecker\Test\Parser;

use Hashbangcode\SitemapChecker\Parser\SitemapXmlParser;
use PHPUnit\Framework\TestCase;

class SitemapXmlParserTest extends TestCase
{
  public function testSitemapParserParsesData()
  {
    $sitemapXml = realpath(__DIR__ . '/../data/sitemap.xml');
    $sitemapXml = file_get_contents($sitemapXml);

    $sitemapXmlParser = new SitemapXmlParser();
    $list = $sitemapXmlParser->parse($sitemapXml);

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