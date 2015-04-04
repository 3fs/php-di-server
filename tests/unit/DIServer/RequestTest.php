<?php
namespace trifs\DIServer\Tests\Unit\Server;

use \trifs\DIServer\Request;

class RequestTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @return void
     */
    public function testInitialisation()
    {
        $request = new Request('GET', '/ping', '{"json":1}', ['x' => 1]);
        $request = new Request('GET', '/ping', '{"json":"x"}');
        $request = new Request('GET', '/ping');
    }

    /**
     * @return void
     */
    public function testRequestUriShouldBeNormalized()
    {
        $request = new Request('GET', '/ping?pong=1');
        $this->assertEquals('/ping', $request->getUri());
    }

    /**
     * @dataProvider getParametersDataProvider
     * @param array      $requestData
     * @param array      $getArgs
     * @param mixed|null $expectedValue
     * @return void
     */
    public function testGet($requestData, $getArgs, $expectedValue)
    {
        $request = new Request($requestData[0], $requestData[1], $requestData[2], $requestData[3]);

        $result = call_user_func_array([$request, 'get'], $getArgs);
        $this->assertEquals($expectedValue, $result);
    }

    /**
     * @return array
     */
    public function getParametersDataProvider()
    {
        return [
            [
                ['GET', '/ping', '', ['pong' => 1]],
                ['pong'],
                1,
            ],
            [
                ['GET', '/ping', '{"pong":1}', []],
                ['pong'],
                1,
            ],
            [
                ['GET', '/ping', '', []],
                ['missing'],
                null,
            ],
            [
                ['GET', '/ping', '', []],
                ['missing', 'default'],
                'default',
            ],
            [
                ['GET', '/ping', '{"pong":1}', ['pong' => 2]],
                ['pong'],
                2,
            ],
        ];
    }

    /**
     * @return void
     */
    public function testGetRequiredShouldReturnValueFromAvailableData()
    {
        $request = new Request('GET', '/ping', '{"key":"value"}');
        $this->assertEquals('value2', $request->getRequired('key2', 'value2'));
        $this->assertEquals(2, $request->getRequired('key2', 2));
    }

    /**
     * @expectedException \trifs\DIServer\Request\ParameterNotFoundException
     * @expectedExceptionMessage Parameter missing is not set.
     * @return void
     */
    public function testGetRequiredShouldThrowAnExceptionWhenKeyIsNotFound()
    {
        $request = new Request('GET', '/ping');
        $request->getRequired('missing');
    }

    /**
     * @return void
     */
    public function testGetMatchingRouteShouldReturnTheCorrectRoute()
    {
        $request       = new Request('GET', '/ping');
        $matchingRoute = $request->getMatchingRoute([
            ['GET', '/ping', 'ping'],
            ['POST', '/pong', 'pong'],
        ]);
        $this->assertEquals(['GET', '/ping', 'ping'], $matchingRoute);
    }

    /**
     * @return void
     */
    public function testGetMatchingRouteShouldReturnTheCorrectRouteWithParameter()
    {
        $request       = new Request('GET', '/hello/world');
        $matchingRoute = $request->getMatchingRoute([
            ['GET', '/hello/{$name}', 'ping'],
            ['POST', '/pong', 'pong'],
        ]);
        $this->assertEquals(['GET', '/hello/{$name}', 'ping'], $matchingRoute);
        $this->assertEquals('world', $request->get('name'));
    }

    /**
     * @expectedException \trifs\DIServer\Request\RouteNotFoundException
     * @expectedExceptionMessage Endpoint GET::/ping is not defined
     * @return void
     */
    public function testGetMatchingRouteShouldThrowAnExceptionWhenRouteIsNotFound()
    {
        $request = new Request('GET', '/ping');
        $request->getMatchingRoute([
            ['POST', '/pong', 'pong'],
        ]);
    }
}
