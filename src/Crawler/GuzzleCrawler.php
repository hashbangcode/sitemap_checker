<?php

namespace Hashbangcode\SitemapChecker\Crawler;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\RequestOptions;
use Hashbangcode\SitemapChecker\HtmlParser\HtmlParser;
use Hashbangcode\SitemapChecker\Result\Result;
use Hashbangcode\SitemapChecker\Result\ResultInterface;
use Hashbangcode\SitemapChecker\Url\UrlInterface;

class GuzzleCrawler extends CrawlerBase
{

    /**
     * @var \GuzzleHttp\Cookie\CookieJarInterface|null
     */
    protected $cookieJar = null;

    public function getCookieJar()
    {
      if ($this->cookieJar === null) {
        $this->cookieJar = new \GuzzleHttp\Cookie\CookieJar();
      }
      return $this->cookieJar;
    }

    public function processUrl(UrlInterface $url): ResultInterface
    {
        $result = new Result($url);
        $htmlParser = new HtmlParser();

        $headers = $this->getOptions()->getHeaders();

        $headers['User-Agent'] = $this->getOptions()->getUserAgent();

        if ($this->getOptions()->hasAuthorization()) {
          $headers['Authorization'] = $this->getOptions()->getAuthorization();
        }

        $request = new Request('GET', $url->getRawUrl());

        $client = $this->getEngine();
        if ($client instanceof Client) {
            try {
              // @todo the first request should add the header auth to the cookie jar.
              // however, the cookie jar is overcomplicating it a little. we only need
              // to pass in the basic header like this.
              // $client = new Client([
              //  'headers'=>[
              //       'Authorization'=> Basic base64_encode(<username>:<password>)
              //   ]
              // ]);
              // cookies might be useful for future work though, so it might be worth keeping.

              $options = [
                RequestOptions::ALLOW_REDIRECTS => false,
                'headers' => $headers,
                'cookies' => $this->getCookieJar(),
              ];
              /** @var \GuzzleHttp\Psr7\Response $response */
              $response = $client->send($request, $options);
            } catch (ClientException|ServerException $e) {
              $response = $e->getResponse();
            } catch (ConnectException $e) {
              // Unable to connect to endpoint due to DNS or other error.
              // @todo : find a better way to represent this.
              $result->setPageSize(0);
              $result->setResponseCode(0);
              return $result;
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
