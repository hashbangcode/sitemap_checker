<?php

namespace Hashbangcode\SitemapChecker\Crawler;

use Hashbangcode\SitemapChecker\Result\ResultCollection;
use Hashbangcode\SitemapChecker\Result\ResultCollectionInterface;
use Hashbangcode\SitemapChecker\UrlCollection;
use Hashbangcode\SitemapChecker\UrlCollectionInterface;

abstract class CrawlerBase implements CrawlerInterface
{
    protected mixed $engine = null;

    public function setEngine(mixed $engine): CrawlerInterface
    {
        $this->engine = $engine;
        return $this;
    }

    public function getEngine(): mixed
    {
        return $this->engine;
    }

    public function crawl(UrlCollectionInterface $urlCollection): ResultCollectionInterface
    {
        $resultCollection = new ResultCollection();
        foreach ($urlCollection as $url) {
            $result = $this->processUrl($url);
            $resultCollection->add($result);
        }
        return $resultCollection;
    }
}
