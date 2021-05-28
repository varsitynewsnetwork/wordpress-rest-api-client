<?php

namespace Vnn\WpApiClient\Endpoint;

/**
 * Class Tags
 * @package Vnn\WpApiClient\Endpoint
 */
class Tags extends AbstractWpEndpoint
{
    /**
     * {@inheritdoc}
     */
    protected function getEndpoint()
    {
        return '/wp/v2/tags';
    }
}
