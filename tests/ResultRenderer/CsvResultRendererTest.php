<?php

namespace Hashbangcode\SitemapChecker\Test\ResultRenderer;

use Hashbangcode\SitemapChecker\Result\Result;
use Hashbangcode\SitemapChecker\Result\ResultCollection;
use Hashbangcode\SitemapChecker\ResultRender\CsvResultRender;
use Hashbangcode\SitemapChecker\Url\Url;
use PHPUnit\Framework\TestCase;

class CsvResultRendererTest extends TestCase
{
  public function testHtmlParserExtractsUrlObjects()
  {
    $url = new Url('https://www.example.com/');
    $result = new Result();
    $result->setUrl($url);
    $result->setTitle('Title');
    $result->setResponseCode(200);

    $resultCollection = new ResultCollection();
    $resultCollection->add($result);

    $csvResultRenderer = new CsvResultRender();
    $csv = $csvResultRenderer->render($resultCollection);
    $this->assertStringContainsString('"https://www.example.com/","Title","200"', $csv);
  }

}