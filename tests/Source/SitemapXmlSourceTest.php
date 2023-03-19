<?php

namespace Hashbangcode\SitemapChecker\Test\Source;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Hashbangcode\SitemapChecker\Parser\SitemapXmlParser;
use Hashbangcode\SitemapChecker\Source\SitemapXmlSource;
use PHPUnit\Framework\TestCase;

class SitemapXmlSourceTest extends TestCase {

  public function testSitemapXmlSourceCreatesValidUrlList()
  {
    $sitemapXml = realpath(__DIR__ . '/../data/sitemap.xml');
    $sitemapXml = file_get_contents($sitemapXml);

    $mock = new MockHandler([
      new Response(200, ['Content-Type' => 'application/xml'], $sitemapXml),
    ]);
    $handlerStack = HandlerStack::create($mock);
    $httpClient = new Client(['handler' => $handlerStack]);

    $sitemapXmlSource = new SitemapXmlSource($httpClient);
    $xmlString = $sitemapXmlSource->fetch('');

    $xmlParse = new SitemapXmlParser();
    $result = $xmlParse->parse($xmlString);

    $this->assertEquals(2, $result->count());
  }

    public function testCompressedSitemapIsUncompressed() {
        $sitemapXml = realpath(__DIR__ . '/../data/sitemap.xml.gz');
        $sitemapXml = file_get_contents($sitemapXml);

        $mock = new MockHandler([
            new Response(200, ['Content-Type' => 'application/xml'], $sitemapXml),
        ]);
        $handlerStack = HandlerStack::create($mock);
        $httpClient = new Client(['handler' => $handlerStack]);

        $sitemapXmlSource = new SitemapXmlSource($httpClient);
        $xmlString = $sitemapXmlSource->fetch('');

        $xmlParse = new SitemapXmlParser();
        $result = $xmlParse->parse($xmlString);

        $this->assertEquals(2, $result->count());
    }
}