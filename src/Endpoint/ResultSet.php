<?php

namespace Vnn\WpApiClient\Endpoint;

use ArrayObject;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\RequestInterface;

/**
 * Class ResultSet
 * @package Vnn\WpApiClient\Endpoint
 */
class ResultSet extends ArrayObject
{
    /**
     * @var int
     */
    public $total = 0;

    /**
     * @var int
     */
    public $totalPages = 0;

    /**
     * @var Psr\Http\Message\RequestInterface
     */
    public $request;

    /**
     * @param RequestInterface $request
     * @param ResponseInterface &$response
     * @return ResultSet
     */
    public function __construct(RequestInterface $request, ResponseInterface &$response)
    {
        $this->request = $request;

        if ($this->validateResponse($response)) {
            parent::__construct(json_decode($response->getBody()->getContents(), true));
            $this->setHeaders($response);
        }
    }

    private function setHeaders(ResponseInterface &$response)
    {
        if ($response->hasHeader('X-WP-Total')) {
            $this->total = (int) $response->getHeader('X-WP-Total')[0];
        }

        if ($response->hasHeader('X-WP-TotalPages')) {
            $this->totalPages = (int) $response->getHeader('X-WP-TotalPages')[0];
        }
    }

    private function validateResponse(ResponseInterface &$response)
    {
        return (
            $response->hasHeader('Content-Type') &&
            substr($response->getHeader('Content-Type')[0], 0, 16) === 'application/json');
    }
}
