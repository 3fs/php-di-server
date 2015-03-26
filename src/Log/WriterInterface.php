<?php
namespace trifs\DIServer\Log;

interface WriterInterface
{
    /**
     * @param  uint   $code
     * @param  string $message
     * @return void
     */
    public function write($level, $message);
}
