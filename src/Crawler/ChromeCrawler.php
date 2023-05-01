<?php

namespace Hashbangcode\SitemapChecker\Crawler;

use Hashbangcode\SitemapChecker\HtmlParser\HtmlParser;
use HeadlessChromium\Browser\ProcessAwareBrowser;
use Hashbangcode\SitemapChecker\Result\Result;
use Hashbangcode\SitemapChecker\Result\ResultInterface;
use Hashbangcode\SitemapChecker\Url\UrlInterface;

class ChromeCrawler extends CrawlerBase
{
    public function processUrl(UrlInterface $url): ResultInterface
    {
        $result = new Result($url);

        $htmlParser = new HtmlParser();

        $browser = $this->getEngine();
        if ($browser instanceof ProcessAwareBrowser) {
            $page = $browser->createPage();
            $page->getSession()->on("method:Network.responseReceived", function (array $params) use ($result): void {
                $result->setResponseCode($params['response']['status']);
                foreach ($params['response']['headers'] as $id => $header) {
                  $params['response']['headers'][$id] = [$header];
                }
                $result->setHeaders($params['response']['headers']);
            });
            $page->navigate($url->getRawUrl())->waitForNavigation();
            $value = (string) $page->evaluate('document.documentElement.outerHTML')->getReturnValue();
            $result->setTitle($htmlParser->extractTitle($value));
        }

        return $result;
    }

}
