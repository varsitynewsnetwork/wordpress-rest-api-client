<?php

namespace Vnn\WpApiClient\Endpoint;

/**
 * Class PostRevisions
 * @package Vnn\WpApiClient\Endpoint
 */
class PostRevisions extends AbstractWpSubEndpoint
{
    /**
     * {@inheritdoc}
     */
    protected function getEndpoint()
    {
        return $this->buildEndpoint('/wp-json/wp/v2/posts/%d/revisions');
    }
}
