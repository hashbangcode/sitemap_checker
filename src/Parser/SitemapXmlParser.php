<?php

namespace Hashbangcode\SitemapChecker\Parser;

use Hashbangcode\SitemapChecker\UrlCollection;
use Hashbangcode\SitemapChecker\UrlCollectionInterface;
use Hashbangcode\SitemapChecker\Url;

class SitemapXmlParser extends ParserBase
{
  public function parse(string $data): UrlCollectionInterface
  {
    $linkCollection = new UrlCollection();

    $xml = simplexml_load_string($data);

    if (isset($xml->url) && count($xml->url) >0) {
      foreach ($xml->url as $url) {
        $link = new Url((string)$url->loc);
        $linkCollection->add($link);
      }
    }

    return $linkCollection;
  }
}