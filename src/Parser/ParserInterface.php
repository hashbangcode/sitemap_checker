<?php

namespace Hashbangcode\SitemapChecker\Parser;

use Hashbangcode\SitemapChecker\UrlCollectionInterface;

interface ParserInterface {
  public function parse(string $data): UrlCollectionInterface;
}