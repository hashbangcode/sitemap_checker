<?php

namespace Hashbangcode\SitemapChecker\Source;

use GuzzleHttp\Psr7\Request;
use Hashbangcode\SitemapChecker\InjectOptions;

class SitemapXmlSource extends SourceBase
{
    use InjectOptions;

    protected bool $isSitemapIndex = false;

    public function isSitemapIndex():bool {
      return $this->isSitemapIndex;
    }

    public function fetch(string $sourceFile): string
    {
        // Ensure the sitemap index detection is initialised to false.
        $this->isSitemapIndex = false;

        $headers['User-Agent'] = $this->getOptions()->getUserAgent();

        $request = new Request('GET', $sourceFile, $headers);

        $options = [];
        if ($this->getOptions()->hasAuthorization()) {
           $options['Authorization'] = $this->getOptions()->getAuthorization();
        }

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
