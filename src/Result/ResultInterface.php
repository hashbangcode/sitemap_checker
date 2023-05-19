<?php

namespace Hashbangcode\SitemapChecker\Result;

use Hashbangcode\SitemapChecker\Url\UrlInterface;

interface ResultInterface
{

    public function getResponseCode(): int;

    public function getUrl(): UrlInterface;

  /**
   * @return array<array<string>>
   */
    public function getHeaders(): array;

  /**
   * @param array<array<string>> $headers
   * @return mixed
   */
    public function setHeaders(array $headers);

  /**
   * Set the title of the page.
   *
   * @param string $string
   *   The title of the page.
   *
   * @return self
   *   The current object.
   */
    public function setTitle(string $string): self;

    public function getTitle():string;

  /**
   * @return int
   */
  public function getPageSize(): int;

  /**
   * @param int $pageSize
   *
   * @return self
   */
  public function setPageSize(int $pageSize): self;

  /**
   * Get the body.
   *
   * @return string
   *   The body.
   */
  public function getBody(): string;

  /**
   * Set the body.
   *
   * @param string $body
   *   The body.
   *
   * @return self
   *   The current object.
   */
  public function setBody(string $body): self;
}
