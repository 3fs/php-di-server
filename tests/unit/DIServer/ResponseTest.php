<?php
namespace trifs\DIServer\Unit\Server;

use \trifs\DIServer\Http;
use \trifs\DIServer\Response;

class ResponseTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @return void
     */
    public function testInitialisation()
    {
        new Response;
    }

    /**
     * @return void
     */
    public function testSuccessShouldOutputPassedDataInJsonFormat()
    {
        $this->expectOutputString('["data"]');
        $response = new Response;
        $response->success([
            'code' => Http::CODE_OK,
            'data' => ['data'],
        ]);
    }

    /**
     * @return void
     */
    public function testSuccessShouldWorkWithoutTheCode()
    {
        $this->expectOutputString('["data"]');
        $response = new Response;
        $response->success([
            'data' => ['data'],
        ]);
    }

    /**
     * @return void
     */
    public function testSuccessShouldOutputEmptyStringWhenDataIsSetToNull()
    {
        $this->expectOutputString('""');
        $response = new Response;
        $response->success([
            'code' => Http::CODE_OK,
            'data' => null,
        ]);
    }

    /**
     * @return void
     */
    public function testFailShouldUseCodeFromServerException()
    {
        $response = $this->getMock('\trifs\DIServer\Response', ['setHeaders']);
        $response->expects($this->once())
            ->method('setHeaders')
            ->with(['code' => 404]);

        $this->expectOutputString('{"error":"message"}');

        $response->fail(new \trifs\DIServer\Exception\ObjectNotFoundException('message'));
    }

    /**
     * @return void
     */
    public function testFailShouldUseDefaultCodeWithUnrecognisedException()
    {
        $response = $this->getMock('\trifs\DIServer\Response', ['setHeaders']);
        $response->expects($this->once())
            ->method('setHeaders')
            ->with(['code' => Http::CODE_INTERNAL_ERROR]);

        $this->expectOutputString('{"error":"message"}');

        $response->fail(new \Exception('message'));
    }

    /**
     * @return void
     */
    public function testRedirect()
    {
        $response = new Response;
        $this->assertSame(
            ['code' => Http::CODE_FOUND, 'data' => ''],
            (new Response)->redirect('some://uri')
        );
    }
}
