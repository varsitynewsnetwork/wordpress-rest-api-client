<?php

namespace Vnn\WpApiClient\Endpoint;

/**
 * Class PostStatuses
 * @package Vnn\WpApiClient\Endpoint
 */
class PostStatuses extends AbstractWpEndpoint
{
    /**
     * {@inheritdoc}
     */
    protected function getEndpoint($path = '')
    {
        return '/wp-json/wp/v2/statuses';
    }
}
