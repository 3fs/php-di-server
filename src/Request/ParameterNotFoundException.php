<?php
namespace trifs\DIServer\Request;

class ParameterNotFoundException extends \trifs\DIServer\Exception
{
    /**
     * @param string|void          $message
     * @param uint|void            $code
     * @param \Exception|null|void $previous
     * @return void
     */
    public function __construct($message = '', $code = 400, $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
