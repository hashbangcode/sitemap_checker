<?php

namespace Hashbangcode\SitemapChecker\Test;

use Hashbangcode\SitemapChecker\Url;
use Hashbangcode\SitemapChecker\UrlCollection;
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

    $this->assertEquals('/', $urlCollection->current()->getPath());
    $urlCollection->next();
    $this->assertEquals('/inner-path', $urlCollection->current()->getPath());

    $urlCollection->delete(1);
    $this->assertEquals($urlCollection->count(), 1);
  }
}