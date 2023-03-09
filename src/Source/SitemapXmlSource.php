<?php

namespace Hashbangcode\SitemapChecker\Source;

use GuzzleHttp\Psr7\Request;

class SitemapXmlSource extends SourceBase
{
    public function fetch(string $sourceFile): string
    {
        $request = new Request('GET', $sourceFile);
        $response = $this->client->send($request);
        $body = (string)$response->getBody();

        if (0 === mb_strpos($body, "\x1f\x8b\x08")) {
            $body = (string)gzdecode($body);
        }
        return $body;
    }
}
