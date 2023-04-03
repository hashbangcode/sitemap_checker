<?php

namespace Hashbangcode\SitemapChecker\Parser;

use Hashbangcode\SitemapChecker\Url\Url;
use Hashbangcode\SitemapChecker\Url\UrlCollection;
use Hashbangcode\SitemapChecker\Url\UrlCollectionInterface;

class SitemapIndexXmlParser extends ParserBase
{
    public function parse(string $data): UrlCollectionInterface
    {
        $linkCollection = new UrlCollection();

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
