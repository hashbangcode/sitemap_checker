<?php

namespace Hashbangcode\SitemapChecker;

class RobotsTxtParser implements RobotsTxtParserInterface {

  public function parse(string $robotsTxt, string $rootUrl): array
  {
    $rules = [];

    $genericUserAgentFound = FALSE;

    $lines = explode("\n", $robotsTxt);

    foreach ($lines as $line) {
      if (str_starts_with($line, '#') || trim($line) == '') {
        continue;
      }
      if (str_contains($line, 'User-agent: *')) {
        $genericUserAgentFound = true;
        continue;
      } elseif ($genericUserAgentFound === true && str_contains($line, 'User-agent: ')) {
        $genericUserAgentFound = false;
        continue;
      }
      if ($genericUserAgentFound === false) {
        continue;
      }
      if (str_starts_with($line, 'Disallow: ')) {
        $line = str_replace('Disallow: ', '', $line);
        $rules[] = $rootUrl . trim($line);
        $rules[] = $rootUrl . trim($line) . '*';
      }
    }

    return $rules;
  }
}