<?php

namespace Hashbangcode\SitemapChecker\Test\ResultRenderer;

use Hashbangcode\SitemapChecker\ResultRender\HtmlResultRender;
use Hashbangcode\SitemapChecker\ResultRender\XmlResultRender;

class HtmlResultRendererTest extends ResultRendererTestBase
{

  public function testHtmlRenderPrintsResultCorrectly()
  {
    $resultRenderer = new HtmlResultRender();
    $renderedResult = $resultRenderer->render($this->resultCollection);
    $this->assertStringContainsString('<li><a target="_blank" href="https://www.example.com/">https://www.example.com/</a></li>', $renderedResult);
    $this->assertStringContainsString('<li><strong>Title:</strong> Title</li>', $renderedResult);
    $this->assertStringContainsString('<li><strong>Response Code:</strong> 200</li>', $renderedResult);
  }

}