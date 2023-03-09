<?php

namespace Hashbangcode\SitemapChecker;

/**
 * @implements \Iterator<int, UrlInterface>
 */
class UrlCollection implements UrlCollectionInterface, \Iterator, \Countable
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
        return !is_null(key($this->urls));
    }

    public function rewind(): void
    {
        reset($this->urls);
    }
}
