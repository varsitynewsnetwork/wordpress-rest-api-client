<?php

namespace Vnn\WpApiClient\Endpoint;

/**
 * Class PostTypes
 * @package Vnn\WpApiClient\Endpoint
 */
class PostTypes extends AbstractWpEndpoint
{
    /**
     * {@inheritdoc}
     */
    protected function getEndpoint()
    {
        return '/wp/v2/types';
    }
}
