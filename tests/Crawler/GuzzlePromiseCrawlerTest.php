<?php

namespace Hashbangcode\SitemapChecker\Test\Crawler;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Hashbangcode\SitemapChecker\Crawler\GuzzlePromiseCrawler;
use Hashbangcode\SitemapChecker\Url\Url;
use Hashbangcode\SitemapChecker\Url\UrlCollection;
use PHPUnit\Framework\TestCase;


class GuzzlePromiseCrawlerTest extends TestCase {

  public function testGuzzlePromiseCrawler() {
    $mock = new MockHandler([
      new Response(200, ['Content-Type' => 'application/html'], '<html><title>Title</title><body><p>Result.</p></body></html>'),
    ]);
    $handlerStack = HandlerStack::create($mock);
    $httpClient = new Client(['handler' => $handlerStack]);

    $urlCollection = new UrlCollection();
    $urlCollection->add(new Url('https://www.example.com/'));

    $guzzleCrawler = new GuzzlePromiseCrawler();
    $guzzleCrawler->setEngine($httpClient);
    $resultsCollection = $guzzleCrawler->crawl($urlCollection);
    $this->assertEquals(200, $resultsCollection->current()->getResponseCode());
    $this->assertEquals('Title', $resultsCollection->current()->getTitle());
    $this->assertEquals('application/html', $resultsCollection->current()->getHeaders()['Content-Type'][0]);
  }
}