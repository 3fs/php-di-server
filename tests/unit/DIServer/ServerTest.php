<?php
namespace trifs\DIServer\Tests\Unit\Server;

use \trifs\DIServer\Http;
use \trifs\DIServer\Server;

class ServerTest extends \trifs\DIServer\Tests\Unit\TestCase
{

    /**
     * @return void
     */
    public function testInitialisation()
    {
        new Server($this->app);
    }

    /**
     * @return void
     */
    public function testRunShouldSendOutputOfTestCallbackToResponse()
    {
        $app = $this->getDI('test');

        $mockedResponse = $this->getMock('\trifs\DIServer\Response', ['success']);
        $mockedResponse->expects($this->once())
                       ->method('success')
                       ->with([
                           'code' => Http::CODE_OK,
                           'data' => 'test',
                       ]);

        $app->response = function () use ($mockedResponse) {
            return $mockedResponse;
        };

        $server = new Server($app);
        $server->run();
    }

    /**
     * @return void
     */
    public function testRunShouldTriggerFailOnResponse()
    {
        $app = $this->getDI('exception');

        $mockCallback = function ($argument) {
            return $argument instanceof \Exception;
        };

        $mockedResponse = $this->getMock('\trifs\DIServer\Response', ['fail']);
        $mockedResponse->expects($this->once())
                       ->method('fail')
                       ->with($this->callback($mockCallback));

        $app->response = function ($app) use ($mockedResponse) {
            return $mockedResponse;
        };

        $server = new Server($app);
        $server->run();
    }

    /**
     * @param string $route
     * @return \trifs\DI\Container
     */
    protected function getDI($route)
    {
        $app = $this->app;

        $app->routes = function ($app) use ($route) {
            return [['GET', '/' . $route, $route]];
        };

        $app->request = function ($app) use ($route) {
            return new \trifs\DIServer\Request(
                'GET',
                '/' . $route
            );
        };

        $app->requestTime = function () {
            return 123;
        };

        $app->log = function () {
            return $this->getMock('SomeLogger', ['info', 'warning']);
        };

        $app->config = function () {
            return [
                'endpoints' => __DIR__ . '/../helpers/endpoints',
            ];
        };

        return $app;
    }
}
