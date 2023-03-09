<?php

namespace Hashbangcode\SitemapChecker\Result;

use Hashbangcode\SitemapChecker\Url;
use Hashbangcode\SitemapChecker\UrlInterface;

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
     * @var Url
     */
    protected Url $url;

    /**
     * The response code.
     *
     * @var string
     */
    protected string $responseCode;

    /**
     * Creates a Result object.
     *
     * @param Url $url
     *   The Url object.
     */
    public function __construct(UrlInterface $url)
    {
        $this->url = $url;
    }

    /**
     * Set the Url object.
     *
     * @param \Hashbangcode\SitemapChecker\Url $url
     *   The url object.
     *
     * @return ResultInterface
     *   The current object.
     */
    public function setUrl($url): ResultInterface
    {
        $this->url = $url;
        return $this;
    }

    /**
     * Set the response code.
     *
     * @param string $responseCode
     *   The response code.
     *
     * @return ResultInterface
     *   The current object.
     */
    public function setResponseCode(string $responseCode): ResultInterface
    {
        $this->responseCode = $responseCode;
        return $this;
    }

    /**
     * Get the response code.
     *
     * @return string
     *   The response code.
     */
    public function getResponseCode(): string
    {
        return $this->responseCode;
    }

    /**
     * Get the Url object.
     *
     * @return Url
     *   The Url object.
     */
    public function getUrl(): Url
    {
        return $this->url;
    }
}
