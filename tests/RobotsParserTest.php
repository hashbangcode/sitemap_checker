<?php

namespace Hashbangcode\SitemapChecker\Test;

use Hashbangcode\SitemapChecker\RobotsTxtParser;
use PHPUnit\Framework\TestCase;

class RobotsParserTest extends TestCase {

  public function testRobotsParserParsesFileIntoRules()
  {
    $robotsTxt = realpath(__DIR__ . '/data/robots1.txt');
    $robotsTxt = file_get_contents($robotsTxt);

    $domain = 'https://www.example.com';

    $robotsParser = new RobotsTxtParser();
    $rules = $robotsParser->parse($robotsTxt, $domain);

    $this->assertEquals(44, count($rules));
    $this->assertEquals($domain . '/core/', $rules[0]);
    $this->assertEquals($domain . '/index.php/user/logout/*', $rules[43]);
  }
}