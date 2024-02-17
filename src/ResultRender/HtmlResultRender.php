<?php

namespace Hashbangcode\SitemapChecker\ResultRender;

use Hashbangcode\SitemapChecker\Result\ResultCollectionInterface;
use PHPStan\Rules\Classes\ExistingClassInInstanceOfRule;

class HtmlResultRender implements ResultRenderInterface {

  public function render(ResultCollectionInterface $resultCollection): string
  {

    $html = '';
    foreach ($resultCollection as $result) {
      $html .= '<ul>';
      $html .= '<li><strong>Title:</strong> ' . $result->getTitle() . '</li>';
      $html .= '<li><a target="_blank" href="' . $result->getUrl()->getRawUrl()  . '">' . $result->getUrl()->getRawUrl() . '</a></li>';
      $html .= '<li><strong>Response Code:</strong> ' . $result->getResponseCode() . '</li>';

      if ($result->getPageSize() > 0) {
        $html .= '<li><strong>Page Size:</strong> ' . $result->getPageSize() . '</li>';
      }
      $html .= '</ul>';
    }

    return <<<EOL
<!DOCTYPE html><html lang="en">
  <head>
    <meta charset="UTF-8">
    <title>Results</title>
  </head>
  <body>
    {$html}
  </body>
</html>
EOL;
  }


}