<?php
namespace trifs\DIServer\Log;

use \trifs\DI\Container;

class ApiFormatter implements FormatterInterface
{
    /**
     * @var \trifs\DI\Container
     */
    private $di;

    /**
     * @var array
     */
    private $logLevelMapping = [
        LOG_EMERG   => 'EMERGENCY',
        LOG_ALERT   => 'ALERT',
        LOG_CRIT    => 'CRITICAL',
        LOG_ERR     => 'ERROR',
        LOG_WARNING => 'WARNING',
        LOG_NOTICE  => 'NOTICE',
        LOG_INFO    => 'INFO',
        LOG_DEBUG   => 'DEBUG',
    ];

    /**
     * @param  \trifs\DI\Container $di
     * @return void
     */
    public function __construct(Container $di)
    {
        $this->di = $di;
    }

    /**
     * @param  int               $level
     * @param  string|\Exception $message
     * @return string            json encoded string
     */
    public function format($level, $message)
    {
        $data = [
            'level'     => $this->mapLogLevel($level),
            'message'   => $message,
            'requestId' => $this->di->requestId,
        ];

        // handle exceptions
        if ($message instanceof \Exception) {
            $data['message']   = $message->getMessage();
            $data['exception'] = [
                'code'    => $message->getCode(),
                'message' => $message->getMessage(),
                'file'    => sprintf('%s:%d', $message->getFile(), $message->getLine()),
                'trace'   => $message->getTrace(),
            ];
        }

        return json_encode($data);
    }

    /**
     * Maps LOG_* constant to a string.
     *
     * @param  int $level
     * @return string
     */
    private function mapLogLevel($level)
    {
        return $this->logLevelMapping[$level];
    }
}
