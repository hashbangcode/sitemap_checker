<?php

namespace Hashbangcode\SitemapChecker\ResultRender;

use Hashbangcode\SitemapChecker\Result\ResultCollectionInterface;

class XmlResultRender implements ResultRenderInterface {

  public function render(ResultCollectionInterface $resultCollection): string
  {
    $xml = new \SimpleXMLElement('<?xml version="1.0"?><results></results>');
    foreach ($resultCollection as $result) {
      $resultXml = $xml->addChild('result');
      $resultXml->addChild('url', $result->getUrl()->getRawUrl());
      $resultXml->addChild('title', $result->getTitle());
      $resultXml->addChild('response_code', $result->getResponseCode());
      if ($result->getPageSize() > 0) {
        $resultXml->addChild('page_size', $result->getPageSize());
      }
    }

    return $xml->asXML();
  }


}