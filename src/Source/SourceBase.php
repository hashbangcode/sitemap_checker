<?php

namespace Hashbangcode\SitemapChecker\Source;

use GuzzleHttp\ClientInterface;

abstract class SourceBase implements SourceInterface
{
    /**
     * @var \GuzzleHttp\ClientInterface
     */
    protected $client;

    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
    }
}
