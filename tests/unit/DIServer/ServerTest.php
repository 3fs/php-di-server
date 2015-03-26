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
        new Server($this->di);
    }

    /**
     * @return void
     */
    public function testRunShouldSendOutputOfTestCallbackToResponse()
    {
        $di = $this->getDI('test');

        $mockedResponse = $this->getMock('\trifs\DIServer\Response', ['success']);
        $mockedResponse->expects($this->once())
                       ->method('success')
                       ->with([
                           'code' => Http::CODE_OK,
                           'data' => 'test',
                       ]);

        $di->response = function () use ($mockedResponse) {
            return $mockedResponse;
        };

        $server = new Server($di);
        $server->run();
    }

    /**
     * @return void
     */
    public function testRunShouldTriggerFailOnResponse()
    {
        $di = $this->getDI('exception');

        $mockedResponse = $this->getMock('\trifs\DIServer\Response', ['fail']);
        $mockedResponse->expects($this->once())
                       ->method('fail')
                       ->with($this->callback(function ($argument) {
            return $argument instanceof \Exception;
        }));

        $di->response = function ($di) use ($mockedResponse) {
            return $mockedResponse;
        };

        $server = new Server($di);
        $server->run();
    }

    /**
     * @param string $route
     * @return \trifs\DI\Container
     */
    protected function getDI($route)
    {
        $di = $this->di;

        $di->routes = function ($di) use ($route) {
            return [['GET', '/' . $route, $route]];
        };

        $di->request = function ($di) use ($route) {
            return new \trifs\DIServer\Request(
                'GET',
                '/' . $route
            );
        };

        $di->requestTime = function () {
            return 123;
        };

        $di->log = function () {
            return $this->getMock('SomeLogger', ['info', 'warning']);
        };

        $di->config = function () {
            return [
                'endpoints' => __DIR__ . '/../helpers/endpoints',
            ];
        };

        return $di;
    }
}
