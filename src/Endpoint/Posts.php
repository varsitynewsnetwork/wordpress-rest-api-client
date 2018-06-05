<?php

namespace Vnn\WpApiClient\Endpoint;

/**
 * Class Posts
 * @package Vnn\WpApiClient\Endpoint
 */
class Posts extends AbstractWpEndpoint
{
    /**
     * {@inheritdoc}
     */
    protected function getEndpoint()
    {
        return '/posts';
    }
}
