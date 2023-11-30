<?php

namespace Vnn\WpApiClient\Endpoint;

class FakeEndpoint extends AbstractWpEndpoint
{
    public function getEndpoint($path = '')
    {
        return '/foo';
    }
}
