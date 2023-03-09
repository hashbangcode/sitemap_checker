<?php

namespace Hashbangcode\SitemapChecker\Source;

interface SourceInterface {

  public function fetch(string $sourceFile):string ;
}