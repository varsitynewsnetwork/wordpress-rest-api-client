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
    protected function getEndpoint()
    {
        return '/statuses';
    }
}
