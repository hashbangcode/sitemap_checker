<?php

namespace Hashbangcode\SitemapChecker\Parser;

use Hashbangcode\SitemapChecker\Url\Url;
use Hashbangcode\SitemapChecker\Url\UrlCollection;
use Hashbangcode\SitemapChecker\Url\UrlCollectionInterface;

class UrlListParser extends ParserBase
{
    public function parse(string $data, array $exclusionRules = []): UrlCollectionInterface
    {
        $linkCollection = new UrlCollection();

        if (count($exclusionRules) > 0) {
           $linkCollection->setExclusionRules($exclusionRules);
        }

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
