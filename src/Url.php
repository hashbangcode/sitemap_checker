<?php

namespace Hashbangcode\SitemapChecker;

class Url implements UrlInterface
{

  protected string $scheme;
  protected string $host;
  protected string $path;
  protected string $query;

  public function __construct(string $url)
  {
    $this->scheme = parse_url($url, PHP_URL_SCHEME) ?: '';
    $this->host = parse_url($url, PHP_URL_HOST) ?: '';
    $this->path = parse_url($url, PHP_URL_PATH) ?: '';
    $this->query = parse_url($url, PHP_URL_QUERY) ?: '';
  }

  public function getHost(): string
  {
    return $this->host;
  }

  public function getPath(): string
  {
    return $this->path;
  }

  public function getScheme(): string
  {
    return $this->scheme;
  }

  public function getQuery(): string
  {
   return $this->query;
  }
}