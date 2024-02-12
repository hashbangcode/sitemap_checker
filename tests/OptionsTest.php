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

  public function testSetMultipleOptions() {
    $options = new Options();
    $options->setUserAgent('Firefox');
    $options->setAuthorization('test');
    $this->assertEquals('Firefox', $options->getUserAgent());
    $this->assertTrue($options->hasAuthorization());
    $this->assertEquals('test', $options->getAuthorization());
    $options->setHeaders([]);
    $this->assertEquals([], $options->getHeaders());
  }

  public function testSetAuthorizationDetails() {
    $options = new Options();
    $this->assertFalse($options->hasAuthorization());
    $options->setAuthorizationDetails('username', 'password');
    $this->assertTrue($options->hasAuthorization());
    $this->assertEquals('Basic dXNlcm5hbWU6cGFzc3dvcmQ=', $options->getAuthorization());
  }
}