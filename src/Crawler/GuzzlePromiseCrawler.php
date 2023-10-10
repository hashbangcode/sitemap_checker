<?php

namespace Hashbangcode\SitemapChecker\Crawler;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Promise\EachPromise;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\RequestOptions;
use Hashbangcode\SitemapChecker\HtmlParser\HtmlParser;
use Hashbangcode\SitemapChecker\Result\Result;
use Hashbangcode\SitemapChecker\Result\ResultCollection;
use Hashbangcode\SitemapChecker\Result\ResultCollectionInterface;
use Hashbangcode\SitemapChecker\Url\UrlCollectionInterface;


class GuzzlePromiseCrawler extends GuzzleCrawler
{

    /**
     * @var \GuzzleHttp\Cookie\CookieJarInterface|null
     */
    protected $cookieJar = null;

    public function getCookieJar() {
      if ($this->cookieJar === null) {
        $this->cookieJar = new \GuzzleHttp\Cookie\CookieJar();
      }
      return $this->cookieJar;
    }

    public function crawl(UrlCollectionInterface $urlCollection): ResultCollectionInterface
    {
        $headers = $this->getOptions()->getHeaders();
        $headers['User-Agent'] = $this->getOptions()->getUserAgent();

        if ($this->getOptions()->hasAuthorization()) {
          $headers['Authorization'] = $this->getOptions()->getAuthorization();
        }

        // Use a generator to create the request.
        $promises = (function () use ($urlCollection, $headers) {
            foreach ($urlCollection as $url) {
                $client = $this->getEngine();
                if ($client instanceof Client) {
                    // @todo the first request should add the header auth to the cookie jar.
                    $options = [
                        RequestOptions::ALLOW_REDIRECTS => false,
                        $headers,
                        'cookies' => $this->getCookieJar(),
                    ];
                    yield $client->getAsync($url->getRawUrl(), $options);
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
            'rejected' =>  function ($exception, $index) use ($urlCollection, $resultCollection, $htmlParser) {
                $url = $urlCollection->find($index);
                if ($exception instanceof ConnectException) {
                    // Unable to connect to endpoint due to DNS or other error.
                    $resultObject = new Result();
                    $resultObject->setResponseCode(0);
                    $resultObject->setPageSize(0);
                    $resultCollection->add($resultObject);
                }
                else {
                    $response = $exception->getResponse();
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
                }
              $urlCollection->delete($index);
            },
        ]);

        $eachPromise->promise()->wait();
        return $resultCollection;
    }
}
