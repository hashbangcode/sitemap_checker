<?php

namespace Hashbangcode\SitemapChecker\Parser;

use Hashbangcode\SitemapChecker\Url;
use Hashbangcode\SitemapChecker\UrlCollection;
use Hashbangcode\SitemapChecker\UrlCollectionInterface;

class UrlListParser extends ParserBase
{
    public function parse(string $data): UrlCollectionInterface
    {
        $linkCollection = new UrlCollection();

        $lines = preg_split("/\r\n|\n|\r/", $data);

        if (is_array($lines) && count($lines) > 0) {
            foreach ($lines as $item) {
                $link = new Url($item);
                $linkCollection->add($link);
            }
        }

        return $linkCollection;
    }
}
