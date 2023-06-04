<?php

namespace Hashbangcode\SitemapChecker\Test\Result;

use Hashbangcode\SitemapChecker\Result\Result;
use Hashbangcode\SitemapChecker\Url\Url;
use PHPUnit\Framework\TestCase;

class ResultTest extends TestCase {

  public function testResultCreation() {
    $url = new Url('https://www.example.com/');
    $result = new Result($url);
    $result->setResponseCode(200);

    $this->assertEquals('200', $result->getResponseCode());
  }

  public function testSettingOfResultUrl() {
      $url = new Url('https://www.example.com');
      $result = new Result();
      $result->setUrl($url);
      $result->setResponseCode(200);

      $this->assertEquals('200', $result->getResponseCode());
  }

    public function testAccessingBlankResultProperties() {
        $result = new Result();

        $this->assertEquals(NULL, $result->getResponseCode());
        $this->assertEquals([], $result->getHeaders());
        $this->assertEquals('', $result->getTitle());
        $this->assertEquals(0, $result->getPageSize());
        $this->assertEquals('', $result->getBody());
    }

}