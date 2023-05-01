<?php

namespace Hashbangcode\SitemapChecker\Result;

use Hashbangcode\SitemapChecker\Url\UrlInterface;

interface ResultInterface
{

    public function getResponseCode(): int;

    public function getUrl(): UrlInterface;

    public function getHeaders(): array;

    public function setHeaders(array $headers);

    public function setTitle(string $string);

    public function getTitle():string;

}
