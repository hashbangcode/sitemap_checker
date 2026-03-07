<?php

namespace Hashbangcode\SitemapChecker\Url;

/**
 * An interface for the URL class.
 */
interface UrlInterface
{

    /**
     * Get the original, raw, URL; as passed to the constructor.
     *
     * @return string
     *   The original raw URL.
     */
    public function getRawUrl(): string;

    public function getScheme(): string;

    public function getHost(): string;

    public function getPort(): ?int;

    public function getPath(): string;

    public function getQuery(): string;

    /**
     * Rebuild the URL based on the parsed elements.
     *
     * Useful if you want to normalise two URLs like "a.com/" and "a.com" that are basically the same.
     *
     * @return string
     *   The rebuild URL.
     */
    public function rebuildUrl(): string;

    /**
     * Generate a hash of the url string.
     *
     * Used for creating filenames for screenshots or for creating hash tables of the urls.
     *
     * @return string
     *   The hash value of the URL, rebuilt using rebuildUrl().
     */
    public function generateHash(): string;
}
