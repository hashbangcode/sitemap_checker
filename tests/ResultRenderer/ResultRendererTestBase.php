<?php

namespace Hashbangcode\SitemapChecker\Test\ResultRenderer;

use Hashbangcode\SitemapChecker\Result\Result;
use Hashbangcode\SitemapChecker\Result\ResultCollection;
use Hashbangcode\SitemapChecker\ResultRender\CsvResultRender;
use Hashbangcode\SitemapChecker\Url\Url;
use PHPUnit\Framework\TestCase;

abstract class ResultRendererTestBase extends TestCase
{

  /**
   * The test result collection.
   *
   * @var ResultCollection
   */
  protected $resultCollection;
  public function setUp(): void
  {
    $url = new Url('https://www.example.com/');
    $result = new Result();
    $result->setUrl($url);
    $result->setTitle('Title');
    $result->setResponseCode(200);

    $this->resultCollection = new ResultCollection();
    $this->resultCollection->add($result);
  }

}