<?php

namespace Hashbangcode\SitemapChecker\Test\Source;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Hashbangcode\SitemapChecker\Parser\UrlListParser;
use Hashbangcode\SitemapChecker\RobotsTxtParser;
use Hashbangcode\SitemapChecker\Source\RobotsTxtSource;
use Hashbangcode\SitemapChecker\Source\UrlListSource;
use PHPUnit\Framework\TestCase;

class RobotsTxtSourceTest extends TestCase {

  public function testRobotsTxtSourceCreatesValidRobotsTxtList()
  {
    $robotsTxt = realpath(__DIR__ . '/../data/robots1.txt');
    $robotsTxt = file_get_contents($robotsTxt);

    $mock = new MockHandler([
      new Response(200, ['Content-Type' => 'txt'], $robotsTxt),
    ]);
    $handlerStack = HandlerStack::create($mock);
    $httpClient = new Client(['handler' => $handlerStack]);

    $robotsTxtSource = new RobotsTxtSource($httpClient);
    $robotsTxtString = $robotsTxtSource->fetch('');

    $robotsParse = new RobotsTxtParser();
    $result = $robotsParse->parse($robotsTxtString, 'https://www.example.com');

    $this->assertEquals(44, count($result));
  }
}