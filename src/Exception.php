<?php
namespace trifs\DIServer;

class Exception extends \RuntimeException
{
    /**
     * @param string|void          $message
     * @param uint|void            $code
     * @param \Exception|null|void $previous
     * @return void
     */
    public function __construct($message = '', $code = 500, $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
