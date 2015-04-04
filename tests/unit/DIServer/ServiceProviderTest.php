<?php
namespace trifs\DIServer\Tests\Unit;

use \trifs\DIServer\ServiceProvider;

class ServiceProviderTest extends TestCase
{
    /**
     * @return void
     */
    public function testRegister()
    {
        $this->app->register(new ServiceProvider);
        $this->assertTrue(isset($this->app->log));
        $this->assertTrue(isset($this->app->request));
        $this->assertTrue(isset($this->app->requestId));
        $this->assertTrue(isset($this->app->response));
        $this->assertTrue(isset($this->app->server));
    }

    // /**
    //  * @return void
    //  */
    // public function testRequestIdWithRequestHeader()
    // {
    //     $this->app->register(new RequestIdServiceProvider);

    //     // spoof request
    //     $this->app->request = function () {
    //         return new Request(
    //             'GET',
    //             '/method',
    //             '',
    //             [],
    //             ['X-Request-Id' => 'headerId']
    //         );
    //     };

    //     $this->assertSame('headerId', $this->app->requestId);
    // }

    // /**
    //  * @return void
    //  */
    // public function testRequestIdWithRandomString()
    // {
    //     $this->app->register(new RequestIdServiceProvider);

    //     // spoof request
    //     $this->app->request = function () {
    //         return new Request('GET', '/method', '', [], []);
    //     };

    //     $this->assertRegExp('/[a-z0-9]{16}/', $this->app->requestId);
    // }
}
