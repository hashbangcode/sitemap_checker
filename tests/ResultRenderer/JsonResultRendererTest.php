<?php

namespace Hashbangcode\SitemapChecker\Test\ResultRenderer;

use Hashbangcode\SitemapChecker\ResultRender\JsonResultRender;

class JsonResultRendererTest extends ResultRendererTestBase
{

  public function testJsonRenderPrintsResultCorrectly()
  {
    $resultRenderer = new JsonResultRender();
    $renderedResult = $resultRenderer->render($this->resultCollection);
    $this->assertStringContainsString('"url":"https://www.example.com/"', $renderedResult);
    $this->assertStringContainsString('"title":"Title"', $renderedResult);
    $this->assertStringContainsString('"response_code":200', $renderedResult);
  }

}