<?php

namespace Hashbangcode\SitemapChecker\Test;

use Hashbangcode\SitemapChecker\Options;
use PHPUnit\Framework\TestCase;

class OptionsTest extends TestCase {

  public function testSetUserAgentOption() {
    $options = new Options();
    $options->setUserAgent('Firefox');
    $this->assertEquals('Firefox', $options->getUserAgent());
  }
}