<?php

namespace Hashbangcode\SitemapChecker\Result;

use Hashbangcode\SitemapChecker\Url\UrlInterface;

interface ResultInterface
{

    public function getResponseCode(): int;

    public function getUrl(): UrlInterface;
}
