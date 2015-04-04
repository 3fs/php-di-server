<?php
namespace trifs\DIServer\Tests\Unit\Log;

use \trifs\DIServer\Log\ApiFormatter;
use \trifs\DIServer\Tests\Unit\TestCase;

class ApiFormatterTest extends TestCase
{
    /**
     * @return void
     */
    public function testFormat()
    {
        // mock request id
        $this->app->requestId = function () {
            return 'reqid';
        };

        $formatter = new ApiFormatter($this->app);
        $this->assertSame(
            json_encode([
                'level'     => 'DEBUG',
                'message'   => 'test123',
                'requestId' => 'reqid',
            ]),
            $formatter->format(LOG_DEBUG, 'test123')
        );
    }

    /**
     * @return void
     */
    public function testFormatWithException()
    {
        // mock request id
        $this->app->requestId = function () {
            return 'reqid';
        };

        $formatter = new ApiFormatter($this->app);
        $output    = $formatter->format(LOG_DEBUG, new \Exception('test'));

        $decodedOutput = json_decode($output, true);

        $this->assertSame('test', $decodedOutput['message']);
        $this->assertEquals(['code', 'message', 'file', 'trace'], array_keys($decodedOutput['exception']));
    }
}
