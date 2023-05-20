<?php

namespace Hashbangcode\SitemapChecker\Crawler;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;
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
            try {
              /** @var \GuzzleHttp\Psr7\Response $response */
              $response = $client->send($request);
            } catch (ClientException $e) {
              $response = $e->getResponse();
            } catch (ServerException $e) {
              $response = $e->getResponse();
            }
            $result->setResponseCode($response->getStatusCode());
            $result->setTitle($htmlParser->extractTitle($response->getBody()->getContents()));
            $result->setHeaders($response->getHeaders());
            $result->setPageSize($response->getBody()->getSize() ?: 0);
            $result->setBody($response->getBody());
        }

        return $result;
    }

}
