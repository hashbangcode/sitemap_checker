<?php

namespace Hashbangcode\SitemapChecker\Url;

class UrlCollection implements UrlCollectionInterface
{
    /**
     * @var UrlInterface[]
     */
    protected array $urls = [];

    public function add(UrlInterface $url): void
    {
        $this->urls[] = $url;
    }

    public function delete(int $index): void
    {
        if (isset($this->urls[$index])) {
            unset($this->urls[$index]);
        }
    }

    public function count(): int
    {
        return count($this->urls);
    }

    public function current(): UrlInterface|false
    {
        return current($this->urls);
    }

    public function next(): void
    {
        next($this->urls);
    }

    public function key(): int
    {
        return (int) key($this->urls);
    }

    public function valid(): bool
    {
        return !(null === key($this->urls));
    }

    public function rewind(): void
    {
        reset($this->urls);
    }

    public function find(int $id) : UrlInterface|false
    {
        if (isset($this->urls[$id])) {
            return $this->urls[$id];
        }
        return false;
    }

  /**
   * {@inheritDoc}
   */
    public function chunk(int $chunkLength) : array
    {
        $collections = [];
        foreach (array_chunk($this->urls, max(1, $chunkLength)) as $urlCollectionChunk) {
            $urlCollection = new UrlCollection();
            foreach ($urlCollectionChunk as $url) {
                $urlCollection->add($url);
            }
            $collections[] = $urlCollection;
        }
        return $collections;
    }

}
