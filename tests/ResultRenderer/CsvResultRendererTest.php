<?php

namespace Hashbangcode\SitemapChecker\Test\ResultRenderer;

use Hashbangcode\SitemapChecker\ResultRender\CsvResultRender;

class CsvResultRendererTest extends ResultRendererTestBase
{
  public function testCsvRenderPrintsResultCorrectly()
  {
    $resultRenderer = new CsvResultRender();
    $renderedResult = $resultRenderer->render($this->resultCollection);
    $this->assertStringContainsString('"https://www.example.com/","Title","200"', $renderedResult);
  }

}