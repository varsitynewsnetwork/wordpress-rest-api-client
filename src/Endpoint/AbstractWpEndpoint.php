<?php

namespace Vnn\WpApiClient\Endpoint;

use GuzzleHttp\Psr7\Request;
use RuntimeException;
use Vnn\WpApiClient\WpClient;

/**
 * Class AbstractWpEndpoint
 * @package Vnn\WpApiClient\Endpoint
 */
abstract class AbstractWpEndpoint
{
    /**
     * @var WpClient
     */
    private $client;

    /**
     * @var bool
     */
    private $public;

    /**
     * Users constructor.
     * @param WpClient $client
     */
    public function __construct(WpClient $client)
    {
        $this->client = $client;
        $this->public = $client->isPublic();
    }

    abstract protected function getEndpoint();

    /**
     * @param int $id
     * @param array $params - parameters that can be passed to GET
     *        e.g. for tags: https://developer.wordpress.org/rest-api/reference/tags/#arguments
     * @return array
     */
    public function get($id = null, array $params = null)
    {
        $uri = $this->getEndpoint();
        $uri .= (is_null($id)?'': '/' . $id);
        $uri .= (is_null($params)?'': '?' . http_build_query($params));

        return $this->sendRequest(new Request('GET', $uri));
    }

    /**
     * @param array $data
     * @return array
     */
    public function save(array $data)
    {
        $url = $this->getEndpoint();

        if (isset($data['id'])) {
            $url .= '/' . $data['id'];
            unset($data['id']);
        }

        return $this->sendRequest(new Request('POST', $url, ['Content-Type' => 'application/json'], json_encode($data)));
    }

    /**
     * @param \GuzzleHttp\Psr7\Request $request
     * @return array
     */
    public function sendRequest(Request $request) {
        $response = $this->client->send($request);
        $results = new ResultSet($request, $response);

       if (count($results)) {
         return $results;
        }

        throw new RuntimeException('Unexpected response');
    }
}
