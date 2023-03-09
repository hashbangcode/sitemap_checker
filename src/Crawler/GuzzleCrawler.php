<?php

namespace Hashbangcode\SitemapChecker\Crawler;

use GuzzleHttp\Psr7\Request;
use Hashbangcode\SitemapChecker\Result\Result;
use Hashbangcode\SitemapChecker\Result\ResultInterface;
use Hashbangcode\SitemapChecker\UrlInterface;

class GuzzleCrawler extends CrawlerBase
{
    public function processUrl(UrlInterface $url): ResultInterface
    {
        $request = new Request('GET', $url->getRawUrl());

        /** @var \GuzzleHttp\Psr7\Response $response */
        $response = $this->getEngine()->send($request);

        $result = new Result($url);
        $result->setResponseCode($response->getStatusCode());
        return $result;
    }
}
