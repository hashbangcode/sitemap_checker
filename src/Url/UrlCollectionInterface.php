<?php

namespace Hashbangcode\SitemapChecker\Url;

/**
 * @extends \Iterator<int, UrlInterface>
 */
interface UrlCollectionInterface extends \Iterator, \Countable
{
    public function add(UrlInterface $link): void;

    public function delete(int $index): void;

    public function find(int $id) : UrlInterface|false;
}
