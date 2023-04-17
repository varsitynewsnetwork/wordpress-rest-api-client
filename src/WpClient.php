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
     * An array of namespaces that are searched for the requested endpoint.
     * By default it contains the default namespace only.
     * Additional namespaces can be added using the method addEndpointNamespace().
     * Newly added namespaces are always prepended, thus will have priority over already existing namespaces.
     * This also means that the default namespace has the lowest priority.
     * @var array
     */
    private $endpointNamespaces = [
        'Vnn\WpApiClient\Endpoint\\',
    ];

    /**
     * WpClient constructor.
     * @param ClientInterface $httpClient
     * @param string $wordpressUrl
     */
    public function __construct(ClientInterface $httpClient, $wordpressUrl = '')
    {
        $this->httpClient = $httpClient;
        $this->wordpressUrl = $wordpressUrl;
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
     * @param $endpoint
     * @param array $args
     * @return Endpoint\AbstractWpEndpoint
     */
    public function __call($endpoint, array $args)
    {
        if (!isset($this->endPoints[$endpoint])) {
            foreach ($this->endpointNamespaces as $namespace) {
                $class = $namespace . ucfirst($endpoint);
                if (class_exists($class)) {
                    $this->endPoints[$endpoint] = new $class($this);
                }
            }
            if (!isset($this->endPoints[$endpoint])) {
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

        $request = $request->withUri(
            $this->httpClient->makeUri($this->wordpressUrl . $request->getUri())
        );

        return $this->httpClient->send($request);
    }

    /**
     * Add a new endpoint namespace that is to be searched for endpoints.
     * @param string $namespace
     */
    public function addEndpointNamespace($namespace)
    {
        array_unshift($this->endpointNamespaces, $namespace);
    }
}
