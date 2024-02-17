<?php

namespace Hashbangcode\SitemapChecker\ResultRender;

use Hashbangcode\SitemapChecker\Result\ResultCollectionInterface;

class PlainResultRender implements ResultRenderInterface {

  public function render(ResultCollectionInterface $resultCollection): string
  {
    $string = '';
    foreach ($resultCollection as $result) {
      $string .= $result->getUrl()->getRawUrl() . ' ' . $result->getTitle() . ' ' . $result->getResponseCode() . PHP_EOL;
    }
    return $string;
  }


}