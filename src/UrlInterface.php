<?php

namespace Hashbangcode\SitemapChecker;

interface UrlInterface
{
  public function getScheme(): string;

  public function getHost(): string;

  public function getPath(): string;

  public function getQuery(): string;

}