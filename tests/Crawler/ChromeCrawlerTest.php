<?php

namespace Hashbangcode\SitemapChecker\Test\Crawler;

use HeadlessChromium\Browser\ProcessAwareBrowser;
use HeadlessChromium\BrowserFactory;
use Hashbangcode\SitemapChecker\Crawler\ChromeCrawler;
use Hashbangcode\SitemapChecker\Url\Url;
use Hashbangcode\SitemapChecker\Url\UrlCollection;
use HeadlessChromium\Communication\Session;
use HeadlessChromium\Page;
use HeadlessChromium\PageUtils\PageEvaluation;
use HeadlessChromium\PageUtils\PageNavigation;
use PHPUnit\Framework\TestCase;


class ChromeCrawlerTest extends TestCase {

  public function testChromeCrawler() {
    $browser = $this->getMockBuilder(ProcessAwareBrowser::class)
      ->disableOriginalConstructor()
      ->getMock();

    $page = $this->getMockBuilder(Page::class)
      ->disableOriginalConstructor()
      ->getMock();

    $pageNavigation = $this->getMockBuilder(PageNavigation::class)
      ->disableOriginalConstructor()
      ->getMock();

    $page->method('navigate')->willReturn($pageNavigation);

    $session = $this->getMockBuilder(Session::class)
      ->disableOriginalConstructor()
      ->getMock();

    $page->method('getSession')->willReturn($session);

    $pageEvaluation = $this->getMockBuilder(PageEvaluation::class)
      ->disableOriginalConstructor()
      ->getMock();

    $pageEvaluation->method('getReturnValue')->willReturn('<html></html>');

    $page->method('evaluate')->willReturn($pageEvaluation);

    $browser->method('createPage')->willReturn($page);

    $urlCollection = new UrlCollection();
    $urlCollection->add(new Url('https://www.example.com/'));

    $chromeCrawler = new ChromeCrawler();
    $chromeCrawler->setEngine($browser);
    $resultsCollection = $chromeCrawler->crawl($urlCollection);
    $this->assertEquals(1, $resultsCollection->count());
    $browser->close();
  }
}