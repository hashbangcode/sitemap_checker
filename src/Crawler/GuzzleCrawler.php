<?php

namespace Hashbangcode\SitemapChecker\Crawler;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use Hashbangcode\SitemapChecker\Result\Result;
use Hashbangcode\SitemapChecker\Result\ResultCollection;
use Hashbangcode\SitemapChecker\Result\ResultCollectionInterface;
use Hashbangcode\SitemapChecker\Result\ResultInterface;
use Hashbangcode\SitemapChecker\UrlCollection;
use Hashbangcode\SitemapChecker\UrlInterface;

class GuzzleCrawler extends CrawlerBase
{
    public function processUrl(UrlInterface $url): ResultInterface
    {
        $result = new Result($url);

        $request = new Request('GET', $url->getRawUrl());

        $client = $this->getEngine();
        if ($client instanceof Client) {
            /** @var \GuzzleHttp\Psr7\Response $response */
            $response = $client->send($request);
            $result->setResponseCode($response->getStatusCode());
        }

        return $result;
    }

}
