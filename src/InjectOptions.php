<?php

namespace Hashbangcode\SitemapChecker;

use Hashbangcode\SitemapChecker\Options;

/**
 * A Trait to allow options to be injected into.
 */
trait InjectOptions {

  /**
   * The crawler options.
   *
   * @var Options
   */
  protected $options = null;

  /**
   * @return Options
   */
  public function getOptions(): Options
  {
    if (null === $this->options) {
      $this->options = new Options();
    }
    return $this->options;
  }

  /**
   * @param Options $options
   *
   * @return self
   */
  public function setOptions(Options $options): self
  {
    $this->options = $options;
    return $this;
  }
}