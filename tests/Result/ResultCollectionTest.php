<?php

namespace Hashbangcode\SitemapChecker\Test\Result;

use Hashbangcode\SitemapChecker\Result\Result;
use Hashbangcode\SitemapChecker\Result\ResultCollection;
use Hashbangcode\SitemapChecker\Url;
use Hashbangcode\SitemapChecker\UrlCollection;
use PHPUnit\Framework\TestCase;

class ResultCollectionTest extends TestCase
{

  public function testResultAddedToResultCollectionIncrementsCountAndIsIterable()
  {
    $resultCollection = new ResultCollection();

    $resultCollection->add(new Result(new Url('https://www.example.com/')));
    $this->assertEquals($resultCollection->count(), 1);

    $resultCollection->add(new Result(new Url('https://www.example.com/inner-path')));
    $this->assertEquals($resultCollection->count(), 2);

      $this->assertEquals(0, $resultCollection->key());
    $this->assertEquals('/', $resultCollection->current()->getUrl()->getPath());
    $resultCollection->next();
      $this->assertEquals(1, $resultCollection->key());
    $this->assertEquals('/inner-path', $resultCollection->current()->getUrl()->getPath());

      foreach ($resultCollection as $id => $result) {
          $this->assertInstanceOf(Result::class, $result);
          $this->assertEquals($id, $resultCollection->key());
      }

    $resultCollection->delete(1);
    $this->assertEquals($resultCollection->count(), 1);
  }
}