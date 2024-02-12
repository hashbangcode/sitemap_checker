<?php

namespace Hashbangcode\SitemapChecker;

/**
 * Options to be passed to the request systems.
 *
 * This includes the crawlers and the sitemap fetcher.
 */
class Options implements OptionsInterface {

  /**
   * The user agent string of the spider bot.
   *
   * @var string
   */
  protected string $userAgent = 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/112.0.0.0 Safari/537.36';

  /**
   * The authorisation header, if set.
   *
   * @var string
   */
  protected string $authorization = '';

  /**
   * Any headers needed to be sent to the endpoint.
   *
   * @var array<string, string>
   */
  protected array $headers = [];

  /**
   * @return string
   */
  public function getUserAgent(): string
  {
    return $this->userAgent;
  }

  /**
   * @param string $userAgent
   * @return Options
   */
  public function setUserAgent(string $userAgent): Options
  {
    $this->userAgent = $userAgent;
    return $this;
  }

  /**
   * @return string
   */
  public function getAuthorization(): string
  {
    return $this->authorization;
  }

  public function setAuthorizationDetails(string $username, string $password): Options
  {
    $this->setAuthorization(sprintf('Basic %s', base64_encode($username . ':' . $password)));
    return $this;
  }

  /**
   * @param string $authorization
   * @return Options
   */
  public function setAuthorization(string $authorization): Options
  {
    $this->authorization = $authorization;
    return $this;
  }

  public function hasAuthorization(): bool
  {
    if ('' === $this->authorization) {
      return false;
    }
    return true;
  }

  /**
   * @return array<string, string>
   */
  public function getHeaders(): array
  {
    return $this->headers;
  }

  /**
   * @param array<string, string> $headers
   *
   * @return Options
   */
  public function setHeaders(array $headers): Options
  {
    $this->headers = $headers;
    return $this;
  }

}
