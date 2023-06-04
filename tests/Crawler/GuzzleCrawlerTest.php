<?php

namespace Hashbangcode\SitemapChecker\Test\Crawler;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Hashbangcode\SitemapChecker\Crawler\GuzzleCrawler;
use Hashbangcode\SitemapChecker\Url\Url;
use Hashbangcode\SitemapChecker\Url\UrlCollection;
use PHPUnit\Framework\TestCase;


class GuzzleCrawlerTest extends TestCase {

  public function testGuzzleCrawler() {
    $body = '<html><title>Title</title><body><p>Result.</p></body></html>';
    $mock = new MockHandler([
      new Response(200, ['Content-Type' => 'application/html'], $body),
    ]);
    $handlerStack = HandlerStack::create($mock);
    $httpClient = new Client(['handler' => $handlerStack]);

    $urlCollection = new UrlCollection();
    $urlCollection->add(new Url('https://www.example.com/'));

    $guzzleCrawler = new GuzzleCrawler();
    $guzzleCrawler->setEngine($httpClient);
    $resultsCollection = $guzzleCrawler->crawl($urlCollection);
    $this->assertEquals(200, $resultsCollection->current()->getResponseCode());
    $this->assertEquals('Title', $resultsCollection->current()->getTitle());
    $this->assertEquals($body, $resultsCollection->current()->getBody());
    $this->assertEquals(mb_strlen($body), $resultsCollection->current()->getPageSize());
    $this->assertEquals('application/html', $resultsCollection->current()->getHeaders()['Content-Type'][0]);
  }

    public function testGuzzle301RedirectCrawler() {
        $body = '';
        $mock = new MockHandler([
            new Response(301, ['Location' => 'https://www.example.com/redirect']),
        ]);
        $handlerStack = HandlerStack::create($mock);
        $httpClient = new Client(['handler' => $handlerStack]);

        $urlCollection = new UrlCollection();
        $urlCollection->add(new Url('https://www.example.com/'));

        $guzzleCrawler = new GuzzleCrawler();
        $guzzleCrawler->setEngine($httpClient);
        $resultsCollection = $guzzleCrawler->crawl($urlCollection);
        $this->assertEquals(301, $resultsCollection->current()->getResponseCode());
        $this->assertEquals('', $resultsCollection->current()->getTitle());
        $this->assertEquals('', $resultsCollection->current()->getBody());
        $this->assertEquals('https://www.example.com/redirect', $resultsCollection->current()->getHeaders()['Location'][0]);
    }

  public function testGuzzleCrawler404Status() {
      $body = '<html><title>404</title><body><p>Not found.</p></body></html>';
      $mock = new MockHandler([
          new ClientException('',
              new Request('GET', 'test'),
              new Response(404, ['Content-Type' => 'application/html'], $body)
          ),
      ]);
      $handlerStack = HandlerStack::create($mock);
      $httpClient = new Client(['handler' => $handlerStack]);

      $urlCollection = new UrlCollection();
      $urlCollection->add(new Url('https://www.example.com/'));

      $guzzleCrawler = new GuzzleCrawler();
      $guzzleCrawler->setEngine($httpClient);
      $resultsCollection = $guzzleCrawler->crawl($urlCollection);
      $this->assertEquals(404, $resultsCollection->current()->getResponseCode());
      $this->assertEquals('404', $resultsCollection->current()->getTitle());
      $this->assertEquals($body, $resultsCollection->current()->getBody());
      $this->assertEquals(mb_strlen($body), $resultsCollection->current()->getPageSize());
      $this->assertEquals('application/html', $resultsCollection->current()->getHeaders()['Content-Type'][0]);
  }

    public function testGuzzleCrawler500Status() {
        $body = '<html><title>500</title><body><p>Error.</p></body></html>';
        $mock = new MockHandler([
            new ServerException('',
                new Request('GET', 'test'),
                new Response(500, ['Content-Type' => 'application/html'], $body)
            ),
        ]);
        $handlerStack = HandlerStack::create($mock);
        $httpClient = new Client(['handler' => $handlerStack]);

        $urlCollection = new UrlCollection();
        $urlCollection->add(new Url('https://www.example.com/'));

        $guzzleCrawler = new GuzzleCrawler();
        $guzzleCrawler->setEngine($httpClient);
        $resultsCollection = $guzzleCrawler->crawl($urlCollection);
        $this->assertEquals(500, $resultsCollection->current()->getResponseCode());
        $this->assertEquals('500', $resultsCollection->current()->getTitle());
        $this->assertEquals($body, $resultsCollection->current()->getBody());
        $this->assertEquals(mb_strlen($body), $resultsCollection->current()->getPageSize());
        $this->assertEquals('application/html', $resultsCollection->current()->getHeaders()['Content-Type'][0]);
    }

    public function testGuzzleCrawlerConnectionError() {
        $mock = new MockHandler([
            new ConnectException('',
                new Request('GET', 'test'),
            ),
        ]);
        $handlerStack = HandlerStack::create($mock);
        $httpClient = new Client(['handler' => $handlerStack]);

        $urlCollection = new UrlCollection();
        $urlCollection->add(new Url('https://www.example.com/'));

        $guzzleCrawler = new GuzzleCrawler();
        $guzzleCrawler->setEngine($httpClient);
        $resultsCollection = $guzzleCrawler->crawl($urlCollection);
        $this->assertEquals(0, $resultsCollection->current()->getResponseCode());
        $this->assertEquals(0, $resultsCollection->current()->getPageSize());
    }
}