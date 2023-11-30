<?php

namespace Vnn\WpApiClient\Endpoint;

/**
 * Class Comments
 * @package Vnn\WpApiClient\Endpoint
 */
class Custom extends AbstractWpEndpoint{

    /**
     * @param $endpoint - Custom endpoint for REST API route. e.g. /acf/v3/
     * 
     */
    protected function getEndpoint($path = '')
    {
        return '/wp-json' . $path;
    }
}