<?php
namespace trifs\DIServer;

use \trifs\DIServer\Log\FormatterInterface as FormatterInt;
use \trifs\DIServer\Log\WriterInterface as WriterInt;

/**
 * A simple interface for logging.
 *
 * The class intentionally breaks PSR rules to simplify the interface.
 * Instead of custom defined constants we rely ones provided with syslog().
 */
class Log
{
    /**
     * @var Log\FormatterInterface
     */
    private $formatter;

    /**
     * @var Log\WriterInterface
     */
    private $writer;

    /**
     * @var int
     */
    private $minLevel;

    /**
     * @param  \trifs\DIServer\Log\FormatterInterface $formatter
     * @param  \trifs\DIServer\Log\WriterInterface    $writer
     * @param  int|void                               $minLevel
     * @return void
     */
    public function __construct(FormatterInt $formatter, WriterInt $writer, $minLevel = LOG_DEBUG)
    {
        $this->formatter = $formatter;
        $this->writer    = $writer;
        $this->minLevel  = $minLevel;
    }

    /**
     * @param  int               $level
     * @param  string|\Exception $message
     * @return void
     */
    public function log($level, $message)
    {
        // only process invocations with sufficient log level
        if ($this->minLevel >= $level) {
            // send parsed message to writer
            $this->writer->write(
                $level,
                $this->formatter->format($level, $message)
            );
        }
    }

    /**
     * System is unusable
     *
     * @param  string|\Exception $message
     * @return void
     */
    public function emergency($message)
    {
        $this->log(LOG_EMERG, $message);
    }

    /**
     * Action must be taken immediately
     *
     * @param  string|\Exception $message
     * @return void
     */
    public function alert($message)
    {
        $this->log(LOG_ALERT, $message);
    }

    /**
     * Critical conditions
     *
     * @param  string|\Exception $message
     * @return void
     */
    public function critical($message)
    {
        $this->log(LOG_CRIT, $message);
    }

    /**
     * Error conditions
     *
     * @param  string|\Exception $message
     * @return void
     */
    public function error($message)
    {
        $this->log(LOG_ERR, $message);
    }

    /**
     * Warning conditions
     *
     * @param  string|\Exception $message
     * @return void
     */
    public function warning($message)
    {
        $this->log(LOG_WARNING, $message);
    }

    /**
     * Normal, but significant, condition
     *
     * @param  string|\Exception $message
     * @return void
     */
    public function notice($message)
    {
        $this->log(LOG_NOTICE, $message);
    }

    /**
     * Informational message
     *
     * @param  string|\Exception $message
     * @return void
     */
    public function info($message)
    {
        $this->log(LOG_INFO, $message);
    }

    /**
     * Debug-level message
     *
     * @param  string|\Exception $message
     * @return void
     */
    public function debug($message)
    {
        $this->log(LOG_DEBUG, $message);
    }
}
