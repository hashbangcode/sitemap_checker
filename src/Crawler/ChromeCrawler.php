<?php

namespace Hashbangcode\SitemapChecker\Crawler;

use HeadlessChromium\Browser\ProcessAwareBrowser;
use Hashbangcode\SitemapChecker\Result\Result;
use Hashbangcode\SitemapChecker\Result\ResultInterface;
use Hashbangcode\SitemapChecker\Url\UrlInterface;

class ChromeCrawler extends CrawlerBase
{
    public function processUrl(UrlInterface $url): ResultInterface
    {
        $result = new Result($url);

        $browser = $this->getEngine();
        if ($browser instanceof ProcessAwareBrowser) {
            $page = $browser->createPage();
            $page->getSession()->on("method:Network.responseReceived", function (array $params) use ($result): void {
                $result->setResponseCode($params['response']['status']);
            });
            $page->navigate($url->getRawUrl())->waitForNavigation();
            $value = $page->evaluate('document.documentElement.outerHTML')->getReturnValue();
        }

        return $result;
    }

}
