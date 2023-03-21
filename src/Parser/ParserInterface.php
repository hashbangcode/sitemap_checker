<?php

namespace Hashbangcode\SitemapChecker\Parser;

use Hashbangcode\SitemapChecker\Url\UrlCollectionInterface;

interface ParserInterface
{
    public function parse(string $data): UrlCollectionInterface;
}
