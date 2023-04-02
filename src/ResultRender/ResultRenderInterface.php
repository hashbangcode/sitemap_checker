<?php

namespace Hashbangcode\SitemapChecker\ResultRender;

use Hashbangcode\SitemapChecker\Result\ResultCollectionInterface;

interface ResultRenderInterface {
  public function render(ResultCollectionInterface $resultCollection): string;
}