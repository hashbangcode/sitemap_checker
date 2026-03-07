<?php

namespace Hashbangcode\SitemapChecker\Url;

/**
 * Class to represent a URL.
 */
class Url implements UrlInterface
{
    protected string $rawUrl;
    protected string $scheme;
    protected string $host;
    protected ?int $port;

    protected string $path;

    protected string $query;

    public function __construct(string $url)
    {
        $url = trim($url);
        $this->rawUrl = $url;
        $this->scheme = parse_url($url, PHP_URL_SCHEME) ?: '';
        $this->host = parse_url($url, PHP_URL_HOST) ?: '';
        $this->port = parse_url($url, PHP_URL_PORT) ?: null;
        $this->path = parse_url($url, PHP_URL_PATH) ?: '';
        $this->query = parse_url($url, PHP_URL_QUERY) ?: '';
    }

    /**
     * {@inheritDoc}
     */
    public function getRawUrl(): string
    {
        return $this->rawUrl;
    }

    public function getHost(): string
    {
        return $this->host;
    }

    public function getPort(): ?int
    {
        return $this->port;
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

    /**
     * {@inheritDoc}
     */
    public function rebuildUrl(): string
    {
        $urlString = $this->getScheme() . '://' . $this->getHost();
        if ($this->getPort() !== null) {
            $urlString .= ':' . $this->getPort();
        }
        if ($this->getPath() !== '') {
            $urlString .= $this->getPath();
        }
        else {
            $urlString .= '/';
        }
        if ($this->getQuery() !== '') {
            $urlString .= '?' . $this->getQuery();
        }
        return $urlString;
    }

    /**
     * {@inheritDoc}
     */
    public function generateHash(): string
    {
        return hash('sha1', $this->rebuildUrl());
    }
}
