<?php
namespace trifs\DIServer\Tests\Integration;

use \trifs\DIServer\Http;

class ApiTest extends \PHPUnit_Framework_TestCase
{
    use \trifs\Shirt\Shirt;

    /**
     * @return void
     */
    public function testRoot()
    {
        $this->execute('GET', '/');
        $this->assertCode(Http::CODE_NOT_FOUND);
        $this->assertResponse('/ not valid');
    }

    /**
     * @return void
     */
    public function testDefaultContentType()
    {
        $this->execute('GET', '/');
        $this->assertHeaderExists('Content-Type', 'application/json; charset=UTF-8');
    }

    /**
     * @return void
     */
    public function testQuery()
    {
        $this->execute('GET', '/query', ['test' => 123]);
        $this->assertCode(Http::CODE_OK);
        $this->assertResponse(['test' => 123]);
    }

    /**
     * @return void
     */
    public function testPostData()
    {
        $this->execute('POST', '/postData', ['test' => 123]);
        $this->assertCode(Http::CODE_OK);
        $this->assertResponse(['test' => 123]);
    }

    /**
     * @return void
     */
    public function testHeaders()
    {
        $this->execute('GET', '/headers', [], ['X-Test: 123']);
        $this->assertCode(Http::CODE_OK);
        $this->assertResponse(['test' => '123']);
    }

    /**
     * @return void
     */
    public function testDefaultCode()
    {
        $this->execute('GET', '/html');
        $this->assertCode(Http::CODE_OK);
    }

    /**
     * @return void
     */
    public function testHtmlContentType()
    {
        $this->execute('GET', '/html');
        $this->assertHeaderExists("Content-Type", "text/html; charset=UTF-8");
    }

    /**
     * @return void
     */
    public function testBadRequest()
    {
        $this->execute('GET', '/query');
        $this->assertCode(Http::CODE_BAD_REQUEST);
    }

    /**
     * @return void
     */
    public function testMissingMethod()
    {
        $this->execute('GET', '/missing');
        $this->assertCode(Http::CODE_NOT_FOUND);
    }

    /**
     * @param  string     $method
     * @param  string     $endpoint
     * @param  array|void $requestData
     * @param  array|void $headers
     * @return void
     */
    protected function execute($method, $endpoint, $requestData = [], $headers = [])
    {
        $headers[] = 'Content-Type: application/json; charset=utf-8';
        $this->$method(
            'http://localhost:8111' . $endpoint,
            json_encode($requestData),
            implode("\n", $headers)
        );
    }

    /**
     * @param  int $httpCode
     * @return void
     */
    protected function assertCode($httpCode)
    {
        $this->assertSame($httpCode, (int) $this->responseCode());
    }

    /**
     * @param  int $httpCode
     * @return void
     */
    protected function assertResponse($data)
    {
        $this->assertSame($data, json_decode($this->response(), true));
    }

    /**
     * Assert a response header was defined
     *
     * @param  string $header
     * @return void
     */
    protected function assertHeaderExists($name, $value)
    {
        $this->assertArrayHasKey($name, $this->headers());
        $this->assertSame($value, $this->headers()[$name]);
    }
}
