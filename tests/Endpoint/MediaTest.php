<?php

namespace Tests\Endpoint;

use Vnn\WpApiClient\Endpoint\Media;
use Vnn\WpApiClient\WpClient;
use GuzzleHttp\Psr7\Request;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use RuntimeException;


class MediaTest extends TestCase
{
    protected function setUp(): void
    {
        $this->wpClient = \Mockery::mock(WpClient::class);
        $this->mediaEndpoint = new Media($this->wpClient);
    }

    public function testUploadThrowsRuntimeExceptionWhenRemoteMissingFile()
    {
        try {
            $this->mediaEndpoint->upload(
                'http://example.com/img/baby-squirrel-eating-pizza.jpg',
                [],
                'image/jpeg'
            );
            $this->fail('Runtime exception should have been thrown');
        } catch (RuntimeException $e) {
            $this->assertEquals(E_WARNING, $e->getCode());
            $this->assertStringContainsString('404 Not Found', $e->getMessage());
        }
    }

    public function testUploadThrowsRuntimeExceptionWhenLocalMissingFile()
    {
        try {
            $this->mediaEndpoint->upload(realpath(dirname('../')) . '/file-does-not-exist.txt');
            $this->fail('Runtime exception should have been thrown');
        } catch (RuntimeException $e) {
            $this->assertEquals(E_WARNING, $e->getCode());
            $this->assertStringContainsString('No such file or directory', $e->getMessage());
        }
    }

    public function testUploadCreatesNewMediaWhenRemoteHasFile()
    {
        $streamResponse = \Mockery::mock(StreamInterface::class);
        $streamResponse->shouldReceive('getContents')->andReturn(json_encode([
            'id' => 32,
            'date' => date('c')
        ]));

        $response = \Mockery::mock(ResponseInterface::class);
        $response->shouldReceive('hasHeader')->with('Content-Type')->andReturn(true);
        $response->shouldReceive('getHeader')->with('Content-Type')->andReturn(['application/json']);
        $response->shouldReceive('getBody')->andReturn($streamResponse);

        $this->wpClient
            ->shouldReceive('send')
            ->with(\Mockery::on(function ($arg) {
                return ($arg instanceof Request) &&
                    $arg->getHeader('Content-Type') == ['text/plain'] &&
                    $arg->getHeader('Content-Disposition') == ['attachment; filename="README.md"'] &&
                    $arg->getMethod() == 'POST';
            }))
            ->andReturn($response);

        $filename = realpath(dirname('../')) . '/README.md';
        $response = $this->mediaEndpoint->upload($filename, [], 'text/plain');
        $this->assertEquals(32, $response['id']);
    }
}
