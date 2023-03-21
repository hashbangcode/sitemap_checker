<?php

namespace Hashbangcode\SitemapChecker\Result;


class ResultCollection implements ResultCollectionInterface
{
    /**
     * @var ResultInterface[]
     */
    protected array $results = [];

    public function add(ResultInterface $result): void
    {
        $this->results[] = $result;
    }

    public function delete(int $index): void
    {
        if (isset($this->results[$index])) {
            unset($this->results[$index]);
        }
    }

    public function count(): int
    {
        return count($this->results);
    }

    public function current(): ResultInterface|false
    {
        return current($this->results);
    }

    public function next(): void
    {
        next($this->results);
    }

    public function key(): int
    {
        return (int) key($this->results);
    }

    public function valid(): bool
    {
        return !is_null(key($this->results));
    }

    public function rewind(): void
    {
        reset($this->results);
    }
}
