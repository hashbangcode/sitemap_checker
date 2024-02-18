<?php

namespace Hashbangcode\SitemapChecker\Parser;

use Hashbangcode\SitemapChecker\Url\UrlCollectionInterface;

interface ParserInterface
{
    /**
     * @param string $data
     * @param array<string> $exclusionRules
     * @return UrlCollectionInterface
     */
    public function parse(string $data, array $exclusionRules = []): UrlCollectionInterface;
}
