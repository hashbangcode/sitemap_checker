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
     * @var mixed
     */
    protected mixed $screenshotFileData = '';

    /**
     * Creates a Result object.
     *
     * @param UrlInterface|null $url
     *   The Url object.
     */
    public function __construct(?UrlInterface $url = NULL)
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

    public function setTitle(string $title): self
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
    public function getBody(): string
    {
        return $this->body;
    }

    /**
     * @inheritDoc
     */
    public function setBody(string $body): self
    {
        $this->body = $body;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getScreenshotFileData(): mixed
    {
        return $this->screenshotFileData;
    }

    /**
     * @inheritDoc
     */
    public function setScreenshotFileData(mixed $screenshotFileData): self
    {
        $this->screenshotFileData = $screenshotFileData;
        return $this;
    }

    public function __toString(): string
    {
        $string = 'URL: ' . $this->getUrl()->rebuildUrl() . PHP_EOL;
        $string .= 'Title: ' . $this->getTitle() . PHP_EOL;
        $string .= 'Response Code: ' . $this->getResponseCode() . PHP_EOL;
        $string .= 'Page Size: ' . $this->getPageSize() . PHP_EOL;
        $string .= 'Headers: ' . print_r($this->getHeaders(), true) . PHP_EOL;
        $string .= 'Body: ' . PHP_EOL . $this->getBody() . PHP_EOL;
        return $string;
    }
}
