<?php

namespace Vnn\WpApiClient;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use RuntimeException;
use Vnn\WpApiClient\Auth\AuthInterface;
use Vnn\WpApiClient\Endpoint;
use Vnn\WpApiClient\Http\ClientInterface;

/**
 * Class WpClient
 * @package Vnn\WpApiClient
 *
 * @method Endpoint\Categories categories()
 * @method Endpoint\Comments comments()
 * @method Endpoint\Media media()
 * @method Endpoint\Pages pages()
 * @method Endpoint\Posts posts()
 * @method Endpoint\PostStatuses postStatuses()
 * @method Endpoint\PostTypes postTypes()
 * @method Endpoint\Tags tags()
 * @method Endpoint\Users users()
 */
class WpClient
{
    /**
     * @var ClientInterface
     */
    private $httpClient;

    /**
     * @var AuthInterface
     */
    private $credentials;

    /**
     * @var string
     */
    private $wordpressUrl;

    /**
     * @var array
     */
    private $endPoints = [];

    /**
     * @var bool
     */
    private $public;

    /**
     * WpClient constructor.
     * @param ClientInterface $httpClient
     * @param string $wordpressUrl
     * @param bool $public
     */
    public function __construct(ClientInterface $httpClient, $wordpressUrl = '', $public = FALSE)
    {
        $this->httpClient = $httpClient;
        $this->wordpressUrl = $wordpressUrl;
        $this->public = $public;
    }

    /**
     * @param $wordpressUrl
     */
    public function setWordpressUrl($wordpressUrl)
    {
        $this->wordpressUrl = $wordpressUrl;
    }

    /**
     * @param AuthInterface $auth
     */
    public function setCredentials(AuthInterface $auth)
    {
        $this->credentials = $auth;
    }

    /**
     * @param bool
     */
    public function setPublic($public = TRUE)
    {
      $this->public = $public;
    }

    /**
     * @return bool
     */
    public function isPublic()
    {
        return (bool) $this->public;
    }

    /**
     * @param $endpoint
     * @param array $args
     * @return Endpoint\AbstractWpEndpoint
     */
    public function __call($endpoint, array $args)
    {
        if (!isset($this->endPoints[$endpoint])) {
            $class = 'Vnn\WpApiClient\Endpoint\\' . ucfirst($endpoint);
            if (class_exists($class)) {
                $this->endPoints[$endpoint] = new $class($this);
            } else {
                throw new RuntimeException('Endpoint "' . $endpoint . '" does not exist"');
            }
        }

        return $this->endPoints[$endpoint];
    }

    /**
     * @param RequestInterface $request
     * @return ResponseInterface
     */
    public function send(RequestInterface $request)
    {
        if ($this->credentials) {
            $request = $this->credentials->addCredentials($request);
        }
        if ($this->isPublic()) {
            $raw_uri = $this->httpClient->makeUri($this->wordpressUrl);
            $public_uri = 'https://public-api.wordpress.com/wp/v2/sites/' . $raw_uri->getHost();
            $uri = $this->httpClient->makeUri($public_uri . $request->getUri());
        }
        else {
            $uri = $this->httpClient->makeUri( $this->wordpressUrl . '/wp-json/wp/v2' . $request->getUri());
        }
        $request = $request->withUri($uri);

        return $this->httpClient->send($request);
    }
}
