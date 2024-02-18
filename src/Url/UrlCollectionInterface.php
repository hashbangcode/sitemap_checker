<?php

namespace Hashbangcode\SitemapChecker\Url;

/**
 * @extends \Iterator<int, UrlInterface>
 */
interface UrlCollectionInterface extends \Iterator, \Countable
{
  public function add(UrlInterface $url): void;

  public function delete(int $index): void;

  public function find(int $id): UrlInterface|false;

  /**
   * @param int $chunkLength
   *
   * @return UrlCollectionInterface[]
   */
  public function chunk(int $chunkLength): array;

  /**
   * @param array<string> $exclusionRules
   *
   * @return self
   */
  public function setExclusionRules(array $exclusionRules): self;

  /**
   * @return array<string>
   */
  public function getExclusionRules(): array;

}
