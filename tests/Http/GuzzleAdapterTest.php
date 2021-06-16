<?php

namespace Tests\Http;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\UriInterface;
use Vnn\WpApiClient\Http\GuzzleAdapter;
use Hamcrest\Matchers as m;


class GuzzleAdapterTest extends TestCase
{
    public function testMakeUriReturnsStringWrappedInGuzzleUriObject()
    {
        $adapter = new GuzzleAdapter();
        $uri = $adapter->makeUri('http://lol.com');

        $this->assertInstanceOf(UriInterface::class, $uri);
        $this->assertEquals('http', $uri->getScheme());
        $this->assertEquals('lol.com', $uri->getHost());
    }

    public function testSendGivesRequestToGuzzleAndReturnsResponse()
    {
        $client = \Mockery::mock(Client::class);
        $adapter = new GuzzleAdapter($client);
        $request = new Psr7\Request('GET', 'foo.com');
        $expectedResponse = new Psr7\Response();
        $client->shouldReceive('send')->with(m::equalTo($request))->andReturn($expectedResponse);

        $response = $adapter->send($request);

        $this->assertEquals($expectedResponse, $response);
    }
}
