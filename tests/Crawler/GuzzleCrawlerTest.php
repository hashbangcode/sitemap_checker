<?php

namespace Hashbangcode\SitemapChecker\Test\Crawler;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Hashbangcode\SitemapChecker\Crawler\GuzzleCrawler;
use Hashbangcode\SitemapChecker\Url;
use Hashbangcode\SitemapChecker\UrlCollection;
use PHPUnit\Framework\TestCase;


class GuzzleCrawlerTest extends TestCase {

  public function testGuzzleCrawler() {
    $mock = new MockHandler([
      new Response(200, ['Content-Type' => 'application/html'], '<html><body><p>Result.</p></body></html>'),
    ]);
    $handlerStack = HandlerStack::create($mock);
    $httpClient = new Client(['handler' => $handlerStack]);

    $urlCollection = new UrlCollection();
    $urlCollection->add(new Url('https://www.example.com/'));

    $guzzleCrawler = new GuzzleCrawler();
    $guzzleCrawler->setEngine($httpClient);
    $resultsCollection = $guzzleCrawler->crawl($urlCollection);
    $this->assertEquals(200, $resultsCollection->current()->getResponseCode());
  }
}