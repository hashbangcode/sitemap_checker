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

    $collectionCount = 0;
    foreach ($urlCollection as $id => $url) {
      $collectionCount++;
        $this->assertInstanceOf(Url::class, $url);
        $this->assertEquals($id, $urlCollection->key());
    }
    $this->assertEquals(2, $collectionCount);

    $urlCollection->delete(1);
    $this->assertEquals($urlCollection->count(), 1);
  }

  public function testUrlFromCollectionCanBeFoundById() {
      $urlCollection = new UrlCollection();
      $urlCollection->add(new Url('https://www.example.com/'));
      $url = $urlCollection->find(0);
      $this->assertEquals('https://www.example.com/', $url->getRawUrl());
  }

  public function testFindMissingUrlFromCollectionReturnsFalse() {
    $urlCollection = new UrlCollection();
    $urlCollection->add(new Url('https://www.example.com/'));
    $url = $urlCollection->find(1);
    $this->assertEquals(false, $url);
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

  public function testRootWildcardUrlExcludesEverything() {
    $urlCollection = new UrlCollection();

    $rules = [
      'https://www.example.com/*',
    ];

    $urlCollection->setExclusionRules($rules);

    $urlCollection->add(new Url('https://www.example.com/'));
    $this->assertEquals($urlCollection->count(), 0);

    $urlCollection->add(new Url('https://www.example.com/excluded-path'));
    $this->assertEquals($urlCollection->count(), 0);

    $urlCollection->add(new Url('https://www.example.com/wildcard-path'));
    $this->assertEquals($urlCollection->count(), 0);

    $urlCollection->add(new Url('https://www.example.com/a-more-complex-yes-wildcarded-path'));
    $this->assertEquals($urlCollection->count(), 0);
  }

  public function testInnerPathUrlIsExcluded() {
    $urlCollection = new UrlCollection();

    $rules = [
      'https://www.example.com/excluded-path',
      'https://www.example.com/wildcard-*',
      'https://www.example.com/*-more-complex-*-wild*-path'
    ];

    $urlCollection->setExclusionRules($rules);

    $urlCollection->add(new Url('https://www.example.com/'));
    $this->assertEquals($urlCollection->count(), 1);

    $urlCollection->add(new Url('https://www.example.com/excluded-path'));
    $this->assertEquals($urlCollection->count(), 1);

    $urlCollection->add(new Url('https://www.example.com/wildcard-path'));
    $this->assertEquals($urlCollection->count(), 1);

    $urlCollection->add(new Url('https://www.example.com/a-more-complex-yes-wildcarded-path'));
    $this->assertEquals($urlCollection->count(), 1);

    $urlCollection->add(new Url('https://www.example.com/non-excluded-path'));
    $this->assertEquals($urlCollection->count(), 2);
  }

  public function testExclusionRulesExcludeRemoteUrl() {
    $urlCollection = new UrlCollection();

    $rules = [
      'https://www.example.com/some-path',
      'https://www.example2.com/some-path',
    ];

    $urlCollection->setExclusionRules($rules);

    $urlCollection->add(new Url('https://www.example.com/'));
    $this->assertEquals($urlCollection->count(), 1);

    $urlCollection->add(new Url('https://www.example2.com/some-path'));
    $this->assertEquals($urlCollection->count(), 1);
  }
}