<?php

namespace Hashbangcode\SitemapChecker\Crawler;

use Hashbangcode\SitemapChecker\Result\ResultCollectionInterface;
use Hashbangcode\SitemapChecker\Result\ResultInterface;
use Hashbangcode\SitemapChecker\UrlCollection;
use Hashbangcode\SitemapChecker\UrlCollectionInterface;
use Hashbangcode\SitemapChecker\UrlInterface;

interface CrawlerInterface
{
    public function setEngine(mixed $engine): CrawlerInterface;

    public function getEngine(): mixed;

    public function crawl(UrlCollectionInterface $urlCollection): ResultCollectionInterface;

    public function processUrl(UrlInterface $url): ResultInterface;
}
