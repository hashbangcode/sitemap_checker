<?php

namespace Hashbangcode\SitemapChecker\Test\Result;

use Hashbangcode\SitemapChecker\Result\Result;
use Hashbangcode\SitemapChecker\Url;
use PHPUnit\Framework\TestCase;

class ResultTest extends TestCase {

  public function testResultCreation() {
    $url = new Url('https://www.example.com/');
    $result = new Result($url);
    $result->setResponseCode(200);

    $this->assertEquals('200', $result->getResponseCode());
  }

}