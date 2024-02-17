<?php

namespace Hashbangcode\SitemapChecker\ResultRender;

use Hashbangcode\SitemapChecker\Result\ResultCollectionInterface;

class JsonResultRender implements ResultRenderInterface {

  public function render(ResultCollectionInterface $resultCollection): string
  {
    $array = [];
    foreach ($resultCollection as $result) {
      $item = [
        'url' => $result->getUrl()->getRawUrl(),
        'title' => $result->getTitle(),
        'response_code' => $result->getResponseCode(),
      ];
      if ($result->getPageSize() > 0) {
        $item['page_size'] = $result->getPageSize();
      }
      $array[] = $item;
    }
    return json_encode($array, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK);;
  }


}