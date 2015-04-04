<?php
namespace trifs\DIServer\Log;

use \trifs\DI\Container;

/**
 * Writes log messages to syslog.
 */
class SyslogWriter implements WriterInterface
{

    /**
     * @var \trifs\DI\Container
     */
    private $app;

    /**
     * @var boolean
     */
    private $logOpen = false;

    /**
     * @var int
     */
    protected $defaultFacility = LOG_USER;

    /**
     * @param  \trifs\DI\Container $app
     * @return void
     */
    public function __construct(Container $app)
    {
        $this->app = $app;
    }

    /**
     * @param  int    $level
     * @param  string $message
     * @return void
     */
    public function write($level, $message)
    {
        $opId       = $this->getApplicationId();
        $opOptions  = LOG_ODELAY;
        $opFacility = $this->getLogFacility();
        if ($this->logOpen || openlog($opId, $opOptions, $opFacility)) {
            $this->logoOpen = true;
            syslog($level, $message);
        }
    }

    /**
     * Reads log facility from config if defined, otherwise returns a default value.
     *
     * @return int
     */
    protected function getLogFacility()
    {
        if (isset($this->app->config['log']['syslog']['facility'])) {
            return $this->app->config['log']['syslog']['facility'];
        }
        return $this->defaultFacility;
    }

    /**
     * Reads application id from config if defined, otherwise returns false.
     *
     * @return string|boolean
     */
    protected function getApplicationId()
    {
        if (isset($this->app->config['log']['syslog']['id'])) {
            return $this->app->config['log']['syslog']['id'];
        }
        return false;
    }
}
