<?php

namespace Hashbangcode\SitemapChecker\Test\Crawler;

use HeadlessChromium\Browser\ProcessAwareBrowser;
use HeadlessChromium\BrowserFactory;
use Hashbangcode\SitemapChecker\Crawler\ChromeCrawler;
use Hashbangcode\SitemapChecker\Url\Url;
use Hashbangcode\SitemapChecker\Url\UrlCollection;
use HeadlessChromium\Page;
use HeadlessChromium\PageUtils\PageNavigation;
use PHPUnit\Framework\TestCase;


class ChromeCrawlerTest extends TestCase {

  public function testChromeCrawler() {
    $chromeBinary = realpath(__DIR__ . '/../../chrome/chrome');
    $browserFactory = new BrowserFactory($chromeBinary);
    $browserFactory->addOptions(['enableImages' => false]);

    $browser = $browserFactory->createBrowser();

    $urlCollection = new UrlCollection();
    $urlCollection->add(new Url('https://www.example.com/'));

    $chromeCrawler = new ChromeCrawler();
    $chromeCrawler->setEngine($browser);
    $resultsCollection = $chromeCrawler->crawl($urlCollection);
    $this->assertEquals(200, $resultsCollection->current()->getResponseCode());
    $browser->close();
  }
}