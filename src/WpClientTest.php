<?php

namespace Vnn\WpApiClient;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use RuntimeException;
use Vnn\WpApiClient\Auth\AuthInterface;
use Vnn\WpApiClient\Endpoint;
use Vnn\WpApiClient\Http\ClientInterface;
use Codeception\Specify;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Argument;
use Vnn\WpApiClient\WpClient;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Uri;
use Vnn\WpApiClient\Http\GuzzleAdapter;

/**
 * Class WpClientTest
 * @package Vnn\WpApiClient
 */
class WpClientTest extends TestCase
{
    use Specify;
    use ProphecyTrait;
    public function test()
    {
        $this->beforeSpecify(function () {
            $this->client = $this->prophesize(ClientInterface::class);

            $this->wordpressUrl = 'http://test.com';

            $this->wpClient = new WpClient($this->client->reveal(), $this->wordpressUrl);
        });

        $this->describe(WpClient::class, function () {
            $this->describe('send()', function () {
                $this->it('should return instance of Response', function () {
                    $request = new Request('GET', '/foo/55');
                    $response = new Response(200, ['Content-Type' => 'application/json'], '{"foo": "bar"}');
                    
                    $guzzleAdapter = (new GuzzleAdapter());
                    $uri = $request->withUri(
                        $guzzleAdapter->makeUri($this->wordpressUrl . $request->getUri())
                    );
                    $this->client->makeUri($this->wordpressUrl.$request->getUri())
                        ->shouldBeCalled()->willReturn(new Uri($this->wordpressUrl . $request->getUri()));

                    $this->client->send($uri)->shouldBeCalled()->willReturn($response);

                    $response = $this->wpClient->send($request);

                    verify($response)->instanceOf(Response::class);
                });
            });
        });
    }
}
