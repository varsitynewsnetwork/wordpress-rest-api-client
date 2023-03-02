<?php

namespace Vnn\WpApiClient\Endpoint;

use Codeception\Specify;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Vnn\WpApiClient\WpClient;
use Vnn\WpApiClient\Endpoint\AbstractWpEndpoint;

/**
 * Class AbstractWpEndpointTest
 * @package Vnn\WpApiClient\Endpoint
 */
class AbstractWpEndpointTest extends TestCase
{
    use Specify;
    public function test()
    {
        $this->beforeSpecify(function () {
            $this->wpClient = $this->prophesize(WpClient::class);

            $this->endpoint = new FakeEndpoint($this->wpClient->reveal());
        });

        $this->describe(AbstractWpEndpoint::class, function () {
            $this->describe('get()', function () {
                $this->it('should make a GET request to the endpoint URL', function () {
                    $request = new Request('GET', '/foo/55');
                    $response = new Response(200, ['Content-Type' => 'application/json'], '{"foo": "bar"}');

                    $this->wpClient->send($request)->shouldBeCalled()->willReturn($response);

                    $data = $this->endpoint->get(55);
                    verify($data)->equals(['foo' => 'bar']);
                });

                $this->it('should make a GET request without any ID', function () {
                    $request = new Request('GET', '/foo');
                    $response = new Response(200, ['Content-Type' => 'application/json'], '{"foo": "bar"}');

                    $this->wpClient->send($request)->shouldBeCalled()->willReturn($response);

                    $data = $this->endpoint->get();
                    verify($data)->equals(['foo' => 'bar']);
                });

                $this->it('should make a GET request with parameters', function () {
                    $request = new Request('GET', '/foo?bar=baz');
                    $response = new Response(200, ['Content-Type' => 'application/json'], '{"foo": "bar"}');

                    $this->wpClient->send($request)->shouldBeCalled()->willReturn($response);

                    $data = $this->endpoint->get(null, ['bar' => 'baz']);
                    verify($data)->equals(['foo' => 'bar']);
                });
            });

            $this->describe('save()', function () {
                $this->it('should make a POST request to the endpoint URL', function () {
                    $response = new Response(200, ['Content-Type' => 'application/json'], '{"foo": "bar"}');

                    $this->wpClient->send(Argument::type(Request::class))->shouldBeCalled()->willReturn($response);

                    $data = $this->endpoint->save(['id' => '1']);
                    verify($data)->equals(['foo' => 'bar']);
                });

                $this->it('should handle unicode data', function () {
                    $response = new Response(
                        200,
                        ['Content-Type' => 'application/json'],
                        '{"first": "Iván", "last": "Peña"}'
                    );

                    $this->wpClient->send(Argument::type(Request::class))->shouldBeCalled()->willReturn($response);

                    $data = $this->endpoint->save(['first' => 'Iván', 'last' => 'Peña']);
                    verify($data)->equals(['first' => 'Iván', 'last' => 'Peña']);
                });
            });

            $this->describe('delete()', function () {
                $this->it('should make a DELETE request to the endpoint URL', function () {
                    $response = new Response(
                        200,
                        ['Content-Type' => 'application/json'],
                        '{"message": "wordpress user has sucessfully deleted"}'
                    );
                    $this->wpClient->send(Argument::type(Request::class))->shouldBeCalled()->willReturn($response);

                    $endpoint = new FakeEndpoint($this->wpClient->reveal());

                    $data = $endpoint->delete(['ID' => '1']);
                    verify($data)->equals(['message' => 'wordpress user has sucessfully deleted']);
                });
            });
        });
    }
}
