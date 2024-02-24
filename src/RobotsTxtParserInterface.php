<?php

namespace Hashbangcode\SitemapChecker;

interface RobotsTxtParserInterface {

  /**
   * Parse a robots.txt file contents to extract the exclusion rules.
   *
   * @param string $robotsTxt
   *   The contents of a robots.txt file.
   * @param string $rootUrl
   *   The root domain to prepend to each of the rules.
   *
   * @return array<string>
   *   The array of exclusion rules.
   */
  public function parse(string $robotsTxt, string $rootUrl): array;
}