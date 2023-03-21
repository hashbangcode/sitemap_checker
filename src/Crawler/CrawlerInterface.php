<?php

namespace Hashbangcode\SitemapChecker\Crawler;

use Hashbangcode\SitemapChecker\Result\ResultCollectionInterface;
use Hashbangcode\SitemapChecker\Result\ResultInterface;
use Hashbangcode\SitemapChecker\Url\UrlCollectionInterface;
use Hashbangcode\SitemapChecker\Url\UrlInterface;

interface CrawlerInterface
{
    public function setEngine(mixed $engine): CrawlerInterface;

    public function getEngine(): mixed;

    public function crawl(UrlCollectionInterface $urlCollection): ResultCollectionInterface;

    public function processUrl(UrlInterface $url): ResultInterface;
}
