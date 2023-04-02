<?php

namespace Hashbangcode\SitemapChecker\ResultRender;

use Hashbangcode\SitemapChecker\Result\ResultCollectionInterface;

class CsvResultRender implements ResultRenderInterface {

  public function render(ResultCollectionInterface $resultCollection): string
  {
    $string = '';
    foreach ($resultCollection as $result) {
      $string .= implode(',',[$result->getUrl()->getRawUrl(),$result->getResponseCode()]).PHP_EOL;
    }
    return $string;
  }


}