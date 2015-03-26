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
        $this->di->register(new RequestIdServiceProvider);
        $this->assertTrue(isset($this->di->requestId));
    }

    /**
     * @return void
     */
    public function testRequestIdWithRequestHeader()
    {
        $this->di->register(new RequestIdServiceProvider);

        // spoof request
        $this->di->request = function () {
            return new Request(
                'GET',
                '/method',
                '',
                [],
                ['X-Request-Id' => 'headerId']
            );
        };

        $this->assertSame('headerId', $this->di->requestId);
    }

    /**
     * @return void
     */
    public function testRequestIdWithRandomString()
    {
        $this->di->register(new RequestIdServiceProvider);

        // spoof request
        $this->di->request = function () {
            return new Request('GET', '/method', '', [], []);
        };

        $this->assertRegExp('/[a-z0-9]{16}/', $this->di->requestId);
    }
}
