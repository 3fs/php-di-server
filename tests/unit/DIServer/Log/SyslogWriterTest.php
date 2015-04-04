<?php
namespace trifs\DIServer\Tests\Unit\Log;

use \trifs\DIServer\Log\SyslogWriter;
use \trifs\DIServer\Tests\Unit\TestCase;

class SyslogWriterTest extends TestCase
{
    /**
     * @return void
     */
    public function testWrite()
    {
        $this->app->config = function () {
            return [
                'log' => [
                    'syslog' => [
                        'id'       => 'UNIT',
                        'facility' => LOG_USER,
                    ],
                ],
            ];
        };
        $writer = new SyslogWriter($this->app);
        $writer->write(LOG_WARNING, 'One message to rule them all');
    }

    /**
     * @return void
     */
    public function testWriteWithoutConfig()
    {
        $this->app->config = function () {
            return [];
        };

        $writer = new SyslogWriter($this->app);
        $writer->write(LOG_WARNING, 'One message to rule them all');
    }
}
