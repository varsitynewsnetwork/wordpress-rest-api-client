<?php

namespace Vnn\WpApiClient\Auth;

use Codeception\Specify;
use GuzzleHttp\Psr7\Request;
use PHPUnit\Framework\TestCase;

/**
 * Class WpBasicAuthTest
 * @package Vnn\WpApiClient\Auth
 */
class WpBasicAuthTest extends TestCase
{
    use Specify;
    public function test()
    {
        $this->describe(WpBasicAuth::class, function () {
            $this->describe('addCredentials()', function () {
                $this->it('should return a request with the proper Authorization header', function () {
                    $auth = new WpBasicAuth('jim', 'hunter2');
                    $request = new Request('GET', '/users');

                    $newRequest = $auth->addCredentials($request);

                    verify($newRequest)->isInstanceOf(Request::class);
                    verify($newRequest->getHeader('Authorization'))->equals([
                        'Basic ' . base64_encode('jim:hunter2')
                    ]);
                });
            });
        });
    }
}
