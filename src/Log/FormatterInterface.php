<?php
namespace trifs\DIServer\Log;

interface FormatterInterface
{
    /**
     * @param  int    $code
     * @param  string $message
     * @return string
     */
    public function format($level, $message);
}
