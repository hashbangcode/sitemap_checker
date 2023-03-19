<?php

namespace Hashbangcode\SitemapChecker\Result;

use Hashbangcode\SitemapChecker\UrlInterface;

interface ResultInterface
{

    public function getResponseCode(): int;

    public function getUrl(): UrlInterface;
}
