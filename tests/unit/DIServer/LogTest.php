<?php
namespace trifs\DIServer\Tests\Unit;

use \trifs\DIServer\Log;

class LogTest extends TestCase
{
    /**
     * @dataProvider dpFunctions
     * @param  array $config
     * @return void
     */
    public function testFunctions($config)
    {
        // set default values
        $config += [
            'message'    => 'message',
            'parameters' => ['message'],
            'minLevel'   => LOG_DEBUG,
            'times'      => $this->once(),
        ];

        // mock formatter
        $formatter = $this->getMock('\trifs\DIServer\Log\FormatterInterface', ['format']);
        $formatter->expects(clone $config['times'])
                  ->method('format')
                  ->with($config['level'], $config['message'])
                  ->willReturn('formatted message');

        // mock writer
        $writer = $this->getMock('\trifs\DIServer\Log\WriterInterface', ['write']);
        $writer->expects(clone $config['times'])
               ->method('write')
               ->with($config['level'], 'formatted message');

        $log = new Log($formatter, $writer, $config['minLevel']);

        call_user_func_array([$log, $config['method']], $config['parameters']);
    }

    /**
     * @return array
     */
    public function dpFunctions()
    {
        return [
            [[
                // normal usage
                'method'     => 'log',
                'parameters' => [LOG_ERR, 'message'],
                'level'      => LOG_ERR,
            ]],
            [[
                // skip due as level is too low
                'method'     => 'log',
                'parameters' => [LOG_INFO, 'message'],
                'level'      => LOG_INFO,
                'minLevel'   => LOG_ALERT,
                'times'      => $this->never(),
            ]],
            [[
                'method' => 'emergency',
                'level'  => LOG_EMERG,
            ]],
            [[
                'method' => 'alert',
                'level'  => LOG_ALERT,
            ]],
            [[
                'method' => 'critical',
                'level'  => LOG_CRIT,
            ]],
            [[
                'method' => 'error',
                'level'  => LOG_ERR,
            ]],
            [[
                'method' => 'WARNING',
                'level'  => LOG_WARNING,
            ]],
            [[
                'method' => 'notice',
                'level'  => LOG_NOTICE,
            ]],
            [[
                'method' => 'info',
                'level'  => LOG_INFO,
            ]],
            [[
                'method' => 'debug',
                'level'  => LOG_DEBUG,
            ]],
        ];
    }
}
