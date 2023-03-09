<?php

namespace Hashbangcode\SitemapChecker\Test;

use Hashbangcode\SitemapChecker\Url;
use PHPUnit\Framework\TestCase;

class UrlTest extends TestCase
{

  public function testLinkContructorAcceptsCorrectParameters()
  {
    $link = new Url('https://www.example.com/inner-path?query=123');
    $this->assertEquals('https', $link->getScheme());
    $this->assertEquals('www.example.com', $link->getHost());
    $this->assertEquals('/inner-path', $link->getPath());
    $this->assertEquals('query=123', $link->getQuery());
  }

  public function testLinkConstructorAcceptsEmptyUrlString()
  {
    $link = new Url('');
    $this->assertEquals('', $link->getScheme());
    $this->assertEquals('', $link->getHost());
    $this->assertEquals('', $link->getPath());
    $this->assertEquals('', $link->getQuery());
  }

}