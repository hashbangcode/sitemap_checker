<?php

namespace Hashbangcode\SitemapChecker\Test\ResultRenderer;

use Hashbangcode\SitemapChecker\ResultRender\XmlResultRender;

class XmlResultRendererTest extends ResultRendererTestBase
{

  public function testXmlRenderPrintsResultCorrectly()
  {
    $resultRenderer = new XmlResultRender();
    $renderedResult = $resultRenderer->render($this->resultCollection);
    $this->assertStringContainsString('<url>https://www.example.com/</url>', $renderedResult);
    $this->assertStringContainsString('<title>Title</title>', $renderedResult);
    $this->assertStringContainsString('<response_code>200</response_code>', $renderedResult);
  }

}