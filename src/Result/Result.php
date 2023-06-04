<?php

namespace Hashbangcode\SitemapChecker\Result;

use Hashbangcode\SitemapChecker\Url\UrlInterface;

/**
 * The Result object.
 *
 * Stores information about what Url was tested and what the response was.
 */
class Result implements ResultInterface
{
    /**
     * The Url object used in the test.
     *
     * @var UrlInterface
     */
    protected UrlInterface $url;

    /**
     * The response code.
     *
     * @var int
     */
    protected ?int $responseCode = null;

  /**
   * The headers in the response.
   *
   * @var array<array<string>>
   */
    protected array $headers = [];

  /**
   * The title of the found page.
   *
   * @var string
   */
    protected string $title = '';

  /**
   * The size of the page.
   *
   * @var int
   */
    protected int $pageSize = 0;

  /**
   * The body.
   *
   * @var string
   */
    protected string $body = '';

    /**
     * Creates a Result object.
     *
     * @param UrlInterface $url
     *   The Url object.
     */
    public function __construct(UrlInterface $url = NULL)
    {
        if ($url !== NULL) {
            $this->url = $url;
        }
    }

    /**
     * Set the Url object.
     *
     * @param \Hashbangcode\SitemapChecker\Url\UrlInterface $url
     *   The url object.
     *
     * @return ResultInterface
     *   The current object.
     */
    public function setUrl(UrlInterface $url): ResultInterface
    {
        $this->url = $url;
        return $this;
    }

    /**
     * Set the response code.
     *
     * @param int $responseCode
     *   The response code.
     *
     * @return ResultInterface
     *   The current object.
     */
    public function setResponseCode(int $responseCode): ResultInterface
    {
        $this->responseCode = $responseCode;
        return $this;
    }

    /**
     * Get the response code.
     *
     * @return int
     *   The response code.
     */
    public function getResponseCode(): ?int
    {
        return $this->responseCode;
    }

    /**
     * Get the Url object.
     *
     * @return UrlInterface
     *   The Url object.
     */
    public function getUrl(): UrlInterface
    {
        return $this->url;
    }

  public function getHeaders(): array
  {
    return $this->headers;
  }

  public function setHeaders(array $headers)
  {
    $this->headers = $headers;
  }

  public function setTitle(string $title):self
  {
    $this->title = $title;
    return $this;
  }

  public function getTitle(): string
  {
    return $this->title;
  }

  /**
   * @inheritDoc
   */
  public function getPageSize(): int
  {
    return $this->pageSize;
  }

  /**
   * @inheritDoc
   */
  public function setPageSize(int $pageSize): self
  {
    $this->pageSize = $pageSize;
    return $this;
  }

  /**
   * @inheritDoc
   */
  public function getBody(): string {
    return $this->body;
  }

  /**
   * @inheritDoc
   */
  public function setBody(string $body): self {
    $this->body = $body;
    return $this;
  }

}
