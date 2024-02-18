<?php

namespace Hashbangcode\SitemapChecker\Parser;

use Hashbangcode\SitemapChecker\Url\Url;
use Hashbangcode\SitemapChecker\Url\UrlCollection;
use Hashbangcode\SitemapChecker\Url\UrlCollectionInterface;

class SitemapIndexXmlParser extends ParserBase
{
    public function parse(string $data, array $exclusionRules = []): UrlCollectionInterface
    {
        $linkCollection = new UrlCollection();

        if (count($exclusionRules) > 0) {
          $linkCollection->setExclusionRules($exclusionRules);
        }

        $xml = simplexml_load_string($data, null, LIBXML_NOWARNING | LIBXML_NOERROR);

        if (isset($xml->sitemap) && count($xml->sitemap) > 0) {
            foreach ($xml->sitemap as $url) {
                $link = new Url((string)$url->loc);
                $linkCollection->add($link);
            }
        }

        return $linkCollection;
    }
}
