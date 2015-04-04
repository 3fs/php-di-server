<?php
namespace trifs\DIServer\Tests\Unit;

use \trifs\DIServer\Request;
use \trifs\DIServer\RequestIdServiceProvider;

class RequestIdServiceProviderTest extends TestCase
{
    /**
     * @return void
     */
    public function testRegister()
    {
        $this->app->register(new RequestIdServiceProvider);
        $this->assertTrue(isset($this->app->requestId));
    }

    /**
     * @return void
     */
    public function testRequestIdWithRequestHeader()
    {
        $this->app->register(new RequestIdServiceProvider);

        // spoof request
        $this->app->request = function () {
            return new Request(
                'GET',
                '/method',
                '',
                [],
                ['X-Request-Id' => 'headerId']
            );
        };

        $this->assertSame('headerId', $this->app->requestId);
    }

    /**
     * @return void
     */
    public function testRequestIdWithRandomString()
    {
        $this->app->register(new RequestIdServiceProvider);

        // spoof request
        $this->app->request = function () {
            return new Request('GET', '/method', '', [], []);
        };

        $this->assertRegExp('/[a-z0-9]{16}/', $this->app->requestId);
    }
}
