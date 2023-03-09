<?php

namespace Hashbangcode\SitemapChecker\Source;

use GuzzleHttp\Psr7\Request;

class UrlListSource extends SourceBase
{
    public function fetch(string $sourceFile): string
    {
        $request = new Request('GET', $sourceFile);
        $response = $this->client->send($request);
        return (string) $response->getBody();
    }
}
