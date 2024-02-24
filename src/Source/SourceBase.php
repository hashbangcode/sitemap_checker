<?php

namespace Hashbangcode\SitemapChecker\Source;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Psr7\Request;

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

  public function fetch(string $sourceFile): string
  {
    $request = new Request('GET', $sourceFile);
    $response = $this->client->send($request);
    return (string) $response->getBody();
  }
}
