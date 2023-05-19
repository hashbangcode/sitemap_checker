<?php

namespace Hashbangcode\SitemapChecker\Url;

class Link extends Url {

  protected string $text;

  /**
   * Get the text.
   *
   * @return string
   *
   */
  public function getText()
  {
    return $this->text;
  }

  /**
   * @param string $text
   *   The text.
   *
   * @return self
   *   The current object.
   */
  public function setText(string $text):self
  {
    $this->text = $text;
    return $this;
  }

}