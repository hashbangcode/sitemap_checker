<?php

namespace Hashbangcode\SitemapChecker\Crawler;

use GuzzleHttp\Client;
use GuzzleHttp\Promise\EachPromise;
use GuzzleHttp\Psr7\Response;
use Hashbangcode\SitemapChecker\HtmlParser\HtmlParser;
use Hashbangcode\SitemapChecker\Result\Result;
use Hashbangcode\SitemapChecker\Result\ResultCollection;
use Hashbangcode\SitemapChecker\Result\ResultCollectionInterface;
use Hashbangcode\SitemapChecker\Url\UrlCollectionInterface;


class GuzzlePromiseCrawler extends GuzzleCrawler
{
    public function crawl(UrlCollectionInterface $urlCollection): ResultCollectionInterface
    {
        // Use a generator to create the .
        $promises = (function () use ($urlCollection) {
            foreach ($urlCollection as $url) {
                $client = $this->getEngine();
                if ($client instanceof Client) {
                    yield $client->getAsync($url->getRawUrl());
                }
            }
        })();

        $resultCollection = new ResultCollection();
        $htmlParser = new HtmlParser();

        $eachPromise = new EachPromise($promises, [
            'concurrency' => 10,
            'fulfilled' => function (Response $response, $index) use ($urlCollection, $resultCollection, $htmlParser) {
                $url = $urlCollection->find($index);
                if ($url !== false) {
                    $resultObject = new Result();
                    $resultObject->setUrl($url);
                    $resultObject->setResponseCode($response->getStatusCode());
                    $resultObject->setTitle($htmlParser->extractTitle($response->getBody()->getContents()));
                    $resultObject->setHeaders($response->getHeaders());
                    $resultObject->setPageSize($response->getBody()->getSize() ?: 0);
                    $resultObject->setBody($response->getBody());
                    $resultCollection->add($resultObject);
                }
                $urlCollection->delete($index);
            },
            'rejected' => function ($reason) {
                // handle promise rejected here
            }
        ]);

        $eachPromise->promise()->wait();
        return $resultCollection;
    }
}
