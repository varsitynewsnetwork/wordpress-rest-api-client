<?php

namespace Vnn\WpApiClient\Endpoint;

/**
 * Class SchoolUsers
 * @package Vnn\WpApiClient\Endpoint
 */
class SchoolUsers extends AbstractWpEndpoint
{
    /**
     * {@inheritdoc}
     */
    protected function getEndpoint()
    {
        return '/wp-json/vnn/v1/school/user';
    }
}
