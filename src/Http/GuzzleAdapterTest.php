<?php

namespace Vnn\WpApiClient\Http;

use Codeception\Specify;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Uri;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\UriInterface;
use Psr\Http\Message\RequestInterface;

/**
 * Class GuzzleAdapterTest
 * @package Vnn\Infrastructure\Http\Client
 */
class GuzzleAdapterTest extends TestCase
{
    use Specify;
    public function test()
    {
        $this->describe(GuzzleAdapter::class, function () {
            $this->describe('makeUri()', function () {
                $this->it('should return a Guzzle Uri object wrapping the string', function () {
                    $adapter = new GuzzleAdapter();
                    $uri = $adapter->makeUri('http://lol.com');

                    verify($uri)->isInstanceOf(UriInterface::class);
                    verify($uri->getScheme())->equals('http');
                    verify($uri->getHost())->equals('lol.com');
                });
            });

            $this->describe('send()', function () {
                $this->it('should pass the request off to Guzzle and return the response', function () {
                    $client = $this->prophesize(Client::class);
                    $adapter = new GuzzleAdapter($client->reveal());

                    $request = new Request('GET', 'foo.com');
                    $expectedResponse = new Response();

                    $client->send($request)->shouldBeCalled()->willReturn($expectedResponse);

                    $response = $adapter->send($request);

                    verify($response)->equals($expectedResponse);
                });
            });
        });
    }
}
