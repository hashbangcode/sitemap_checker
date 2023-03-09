<?php

namespace Hashbangcode\SitemapChecker;

interface UrlCollectionInterface
{

  public function add(UrlInterface $link):void;

  public function delete(int $index):void;
}