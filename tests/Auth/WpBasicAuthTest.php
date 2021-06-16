<?php

namespace Tests\Auth;

use GuzzleHttp\Psr7\Request;
use PHPUnit\Framework\TestCase;
use Vnn\WpApiClient\Auth\WpBasicAuth;

class WpBasicAuthTest extends TestCase
{
    public function testAddCredentialsReturnsProperAuthorizationHeader()
    {
        $auth = new WpBasicAuth('jim', 'hunter2');
        $request = new Request('GET', '/users');
        
        $newRequest = $auth->addCredentials($request);

        $this->assertInstanceOf(Request::class, $newRequest);

        $this->assertEquals(
            ['Basic ' . base64_encode('jim:hunter2')], 
            $newRequest->getHeader('Authorization')
        );
    }
}
