<?php

namespace Hashbangcode\SitemapChecker\Test\Url;

use Hashbangcode\SitemapChecker\Url\Url;
use Hashbangcode\SitemapChecker\Url\UrlCollection;
use PHPUnit\Framework\TestCase;

class UrlCollectionTest extends TestCase
{

  public function testUrlAddedToUrlCollectionIncrementsCountAndIsIterable()
  {
    $urlCollection = new UrlCollection();

    $urlCollection->add(new Url('https://www.example.com/'));
    $this->assertEquals($urlCollection->count(), 1);

    $urlCollection->add(new Url('https://www.example.com/inner-path'));
    $this->assertEquals($urlCollection->count(), 2);

    $this->assertEquals(0, $urlCollection->key());
    $this->assertEquals('/', $urlCollection->current()->getPath());
    $urlCollection->next();
    $this->assertEquals(1, $urlCollection->key());
    $this->assertEquals('/inner-path', $urlCollection->current()->getPath());

    foreach ($urlCollection as $id => $url) {
        $this->assertInstanceOf(Url::class, $url);
        $this->assertEquals($id, $urlCollection->key());
    }

    $urlCollection->delete(1);
    $this->assertEquals($urlCollection->count(), 1);
  }

  public function testUrlFromCollectionCanBeFoundById() {
      $urlCollection = new UrlCollection();
      $urlCollection->add(new Url('https://www.example.com/'));
      $url = $urlCollection->find(0);
      $this->assertEquals('https://www.example.com/', $url->getRawUrl());
  }

  public function testUrlCollectionChunk() {
      $urlCollection = new UrlCollection();
      $urlCollection->add(new Url('https://www.example.com/'));
      $urlCollection->add(new Url('https://www.example.com/inner-path'));

      $urlCollections = $urlCollection->chunk(1);
      $this->assertEquals(2, count($urlCollections));
      foreach ($urlCollections as $collection) {
          $this->assertEquals(1, $collection->count());
      }
  }
}