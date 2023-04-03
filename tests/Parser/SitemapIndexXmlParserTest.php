<?php

namespace Hashbangcode\SitemapChecker\Test\Parser;

use Hashbangcode\SitemapChecker\Parser\SitemapIndexXmlParser;
use Hashbangcode\SitemapChecker\Parser\SitemapXmlParser;
use PHPUnit\Framework\TestCase;

class SitemapIndexXmlParserTest extends TestCase
{
  public function testSitemapParserParsesData()
  {
    $sitemapIndexXml = realpath(__DIR__ . '/../data/sitemap-index.xml');
    $sitemapIndexXml = file_get_contents($sitemapIndexXml);

    $sitemapIndexXmlParser = new SitemapIndexXmlParser();
    $list = $sitemapIndexXmlParser->parse($sitemapIndexXml);

    $this->assertEquals(2, $list->count());

    $this->assertEquals('https://www.example.com/sitemap1.xml', $list->current()->getRawUrl());

    $list->next();

    $this->assertEquals('https://www.example.com/sitemap2.xml', $list->current()->getRawUrl());
  }

}