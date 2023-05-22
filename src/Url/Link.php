<?php

namespace Hashbangcode\SitemapChecker\Url;

/**
 * Class to represent a Link.
 *
 * This is a Url with some content.
 */
class Link extends Url {

  /**
   * The content of the link.
   *
   * @var string
   */
  protected string $text;

  /**
   * Get the content of the link.
   *
   * @return string
   *   The content of the link.
   */
  public function getText()
  {
    return $this->text;
  }

  /**
   * Set the content of the link.
   *
   * @param string $text
   *   The content of the link.
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