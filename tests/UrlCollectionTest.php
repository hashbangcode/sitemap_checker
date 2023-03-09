<?php

namespace Hashbangcode\SitemapChecker\Test;

use Hashbangcode\SitemapChecker\Url;
use Hashbangcode\SitemapChecker\UrlCollection;
use PHPUnit\Framework\TestCase;

class UrlCollectionTest extends TestCase
{

  public function testLinkAddedToLinkCollectionIncrementsCountAndIsIterable()
  {
    $linkCollection = new UrlCollection();

    $linkCollection->add(new Url('https://www.example.com/'));
    $this->assertEquals($linkCollection->count(), 1);

    $linkCollection->add(new Url('https://www.example.com/inner-path'));
    $this->assertEquals($linkCollection->count(), 2);

    $this->assertEquals('/', $linkCollection->current()->getPath());
    $linkCollection->next();
    $this->assertEquals('/inner-path', $linkCollection->current()->getPath());

    $linkCollection->delete(1);
    $this->assertEquals($linkCollection->count(), 1);
  }
}