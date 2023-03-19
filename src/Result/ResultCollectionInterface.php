<?php

namespace Hashbangcode\SitemapChecker\Result;

use Hashbangcode\SitemapChecker\Result\ResultInterface;

/**
 * @extends \Iterator<int, ResultInterface>
 */
interface ResultCollectionInterface extends \Iterator, \Countable
{
}
