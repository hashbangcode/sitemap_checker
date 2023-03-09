<?php

namespace Hashbangcode\SitemapChecker\Test\Source;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Hashbangcode\SitemapChecker\Parser\UrlListParser;
use Hashbangcode\SitemapChecker\Source\UrlListSource;
use PHPUnit\Framework\TestCase;

class UrlListSourceTest extends TestCase {

  public function testUrlListSourceCreatesValidUrlList()
  {
    $urlList = realpath(__DIR__ . '/../data/urllist.txt');
    $urlList = file_get_contents($urlList);

    $mock = new MockHandler([
      new Response(200, ['Content-Type' => 'txt'], $urlList),
    ]);
    $handlerStack = HandlerStack::create($mock);
    $httpClient = new Client(['handler' => $handlerStack]);

    $urlListSource = new UrlListSource($httpClient);
    $urlListString = $urlListSource->fetch('');

    $urlParse = new UrlListParser();
    $result = $urlParse->parse($urlListString);

    $this->assertEquals(2, $result->count());
  }
}