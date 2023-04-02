<?php

namespace Hashbangcode\SitemapChecker\Parser;

use Hashbangcode\SitemapChecker\Url\Url;
use Hashbangcode\SitemapChecker\Url\UrlCollection;
use Hashbangcode\SitemapChecker\Url\UrlCollectionInterface;

class SitemapXmlParser extends ParserBase
{
    public function parse(string $data): UrlCollectionInterface
    {
        $linkCollection = new UrlCollection();

        $xml = simplexml_load_string($data, null, LIBXML_NOWARNING | LIBXML_NOERROR);

        if (isset($xml->url) && count($xml->url) > 0) {
            foreach ($xml->url as $url) {
                $link = new Url((string)$url->loc);
                $linkCollection->add($link);
            }
        }

        return $linkCollection;
    }
}
