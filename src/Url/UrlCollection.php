<?php

namespace Hashbangcode\SitemapChecker\Url;

class UrlCollection implements UrlCollectionInterface
{
    /**
     * @var UrlInterface[]
     */
    protected array $urls = [];

    /**
     * Rules for excluding URLs from the collection.
     *
     * @var array<string>
     */
    protected array $exclusionRules = [];

    public function add(UrlInterface $url): void
    {
      if (count($this->exclusionRules) === 0) {
        // There are no exclusion rules, so add the URL and return.
        $this->urls[] = $url;
        return;
      }

      foreach ($this->exclusionRules as $rule) {
        // Perform a like for like match.
        if ($url->getRawUrl() === $rule) {
          return;
        }
        // Perform a wildcard match.
        if (str_contains($rule, '*') && preg_match('/^' . $rule . '/i', $url->getRawUrl()) > 0) {
          return;
        }
      }
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

  /**
   * {@inheritDoc}
   */
    public function setExclusionRules(array $exclusionRules): self
    {
      foreach ($exclusionRules as &$rule) {
        if (str_contains($rule, '*')) {
          $rule = preg_quote($rule, '/');
          $rule = str_replace('\*', '.*', $rule);
        }
      }
      $this->exclusionRules = $exclusionRules;
      return $this;
    }

  /**
   * {@inheritDoc}
   */
    public function getExclusionRules(): array
    {
      return $this->exclusionRules;
    }
}
