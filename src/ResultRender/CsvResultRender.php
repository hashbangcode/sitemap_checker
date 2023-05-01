<?php

namespace Hashbangcode\SitemapChecker\ResultRender;

use Hashbangcode\SitemapChecker\Result\ResultCollectionInterface;

class CsvResultRender implements ResultRenderInterface {

  public function render(ResultCollectionInterface $resultCollection): string
  {
    $string = '';
    foreach ($resultCollection as $result) {
      $line = [
        $result->getUrl()->getRawUrl(),
        $result->getTitle(),
        $result->getResponseCode(),
      ];
      $string .= '"' . implode('","', $line) . '"' . PHP_EOL;
    }
    return $string;
  }


}