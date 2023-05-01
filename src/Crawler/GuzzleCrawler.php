<?php

namespace Hashbangcode\SitemapChecker\Crawler;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use Hashbangcode\SitemapChecker\HtmlParser\HtmlParser;
use Hashbangcode\SitemapChecker\Result\Result;
use Hashbangcode\SitemapChecker\Result\ResultInterface;
use Hashbangcode\SitemapChecker\Url\UrlInterface;

class GuzzleCrawler extends CrawlerBase
{
    public function processUrl(UrlInterface $url): ResultInterface
    {
        $result = new Result($url);
        $htmlParser = new HtmlParser();

        $request = new Request('GET', $url->getRawUrl());

        $client = $this->getEngine();
        if ($client instanceof Client) {
            /** @var \GuzzleHttp\Psr7\Response $response */
            $response = $client->send($request);
            $result->setResponseCode($response->getStatusCode());
            $result->setTitle($htmlParser->extractTitle($response->getBody()->getContents()));
            $result->setHeaders($response->getHeaders());
        }

        return $result;
    }

}
