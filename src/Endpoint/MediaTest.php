<?php

namespace Vnn\WpApiClient\Endpoint;

use Codeception\Specify;
use Codeception\AssertThrows;
use GuzzleHttp\Psr7\Request;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use RuntimeException;
use Vnn\WpApiClient\WpClient;
use Vnn\WpApiClient\Endpoint\Media;

/**
 * Class MediaTest
 * @package Vnn\WpApiClient\Endpoint
 */
class MediaTest extends TestCase
{
    use Specify;
    use AssertThrows;
    use ProphecyTrait;
    public function test()
    {
        $this->beforeSpecify(function () {
            $this->wpClient = $this->prophesize(WpClient::class);
            $this->mediaEndpoint = new Media($this->wpClient->reveal());
        });

        $this->describe('when the file to be uploaded is not found', function () {
            $this->it('should throw a Runtime Exception for a missing remote file', function () {
                $imgUrl = 'http://example.com/img/baby-squirrel-eating-pizza.jpg';
                $contentType = 'image/jpeg';
                set_error_handler(function (int $errno, string $errstr, string $errfile) {
                    verify($errno)->equals(E_WARNING);
                    verify(strpos($errstr, '404 Not Found'))->notEquals(false);
                });

                $this->assertThrowsWithMessage(
                    RuntimeException::class,
                    'Unexpected response',
                    [$this->mediaEndpoint, 'upload'],
                    $imgUrl,
                    [],
                    $contentType
                );

                restore_error_handler();
            });

            $this->it('should throw a Runtime Exception for a missing local file', function () {
                $filename = realpath(dirname('../')) . '/file-does-not-exist.txt';

                set_error_handler(function (int $errno, string $errstr, string $errfile) {
                    verify($errno)->equals(E_WARNING);
                    verify(strpos($errstr, ' No such file or directory'))->notEquals(false);
                });

                $this->assertThrows(
                    RuntimeException::class,
                    [$this->mediaEndpoint, 'upload'],
                    $filename
                );

                restore_error_handler();
            });
        });

        $this->describe('when the file to be upload exists', function () {
            $this->it('should attempt to create a new media item using a POST request', function () {
                // mocking the response...
                $streamResponse = $this->prophesize(StreamInterface::class);
                $streamResponse->getContents()->shouldBeCalled()->willReturn(
                    json_encode(['id' => 32, 'date' => (new \DateTime())->format('c')])
                );

                $response = $this->prophesize(ResponseInterface::class);
                $response->hasHeader('Content-Type')->shouldBeCalled()->willReturn(true);
                $response->getHeader('Content-Type')->shouldBeCalled()->willReturn(['application/json']);
                $response->getBody()->shouldBeCalled()->willReturn($streamResponse->reveal());

                $this->wpClient
                    ->send(Argument::that(function ($arg) {
                        return ($arg instanceof Request) &&
                            $arg->getHeader('Content-Type') == ['text/plain'] &&
                            $arg->getHeader('Content-Disposition') == ['attachment; filename="README.md"'] &&
                            $arg->getMethod() == 'POST'
                        ;
                    }))
                    ->shouldBeCalled()
                    ->willReturn($response->reveal());

                $filename = realpath(dirname('../')) . '/README.md';
                $response = $this->mediaEndpoint->upload($filename);
                verify($response['id'])->equals(32);
            });
        });
    }
}
