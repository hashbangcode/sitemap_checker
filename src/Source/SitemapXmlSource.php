<?php

namespace Hashbangcode\SitemapChecker\Source;

use GuzzleHttp\Psr7\Request;

class SitemapXmlSource extends SourceBase
{
    protected bool $isSitemapIndex = false;

    public function isSitemapIndex():bool {
      return $this->isSitemapIndex;
    }

    public function fetch(string $sourceFile): string
    {
        // Ensure the sitemap index detection is initialised to false.
        $this->isSitemapIndex = false;

        $headers = [
            'User-Agent' => 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/112.0.0.0 Safari/537.36'
        ];

        $request = new Request('GET', $sourceFile, $headers);

        // @todo this part needs to be injected.
        $options = [
          'auth' => [
            'test',
            'test',
          ]
        ];
        $response = $this->client->send($request, $options);
        $body = (string)$response->getBody();

        if (0 === mb_strpos($body, "\x1f\x8b\x08")) {
            $body = (string)gzdecode($body);
        }

        if (str_contains($body, '<sitemapindex ') === true) {
          // This is a sitemap index file.
          $this->isSitemapIndex = true;
        }

        return $body;
    }
}
