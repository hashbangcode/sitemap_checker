<?php

namespace Hashbangcode\SitemapChecker\Test\ResultRenderer;

use Hashbangcode\SitemapChecker\ResultRender\PlainResultRender;

class PlainResultRendererTest extends ResultRendererTestBase
{

  public function testPlainRenderPrintsResultCorrectly()
  {
    $resultRenderer = new PlainResultRender();
    $renderedResult = $resultRenderer->render($this->resultCollection);
    $this->assertStringContainsString('https://www.example.com/ Title 200', $renderedResult);
  }

}