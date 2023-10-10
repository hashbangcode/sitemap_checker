<?php

namespace Hashbangcode\SitemapChecker\Crawler;

use Hashbangcode\SitemapChecker\Options;
use Hashbangcode\SitemapChecker\Result\ResultCollection;
use Hashbangcode\SitemapChecker\Result\ResultCollectionInterface;
use Hashbangcode\SitemapChecker\Url\UrlCollectionInterface;

abstract class CrawlerBase implements CrawlerInterface
{
    /**
     * The crawler options.
     *
     * @var Options
     */
    protected $options = null;

    protected mixed $engine = null;

    public function setEngine(mixed $engine): CrawlerInterface
    {
        $this->engine = $engine;
        return $this;
    }

    public function getEngine(): mixed
    {
        return $this->engine;
    }

    /**
     * @return Options
     */
    public function getOptions(): Options
    {
      if (null === $this->options) {
        $this->options = new Options();
      }
      return $this->options;
    }

    /**
     * @param Options $options
     *
     * @return self
     */
    public function setOptions(Options $options): CrawlerBase
    {
      $this->options = $options;
      return $this;
    }

    public function crawl(UrlCollectionInterface $urlCollection): ResultCollectionInterface
    {
        $resultCollection = new ResultCollection();
        foreach ($urlCollection as $url) {
            $result = $this->processUrl($url);
            $resultCollection->add($result);
        }
        return $resultCollection;
    }
}
