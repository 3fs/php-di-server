<?php
namespace trifs\DIServer;

use \trifs\DIServer\Request\InvalidRouteException;
use \trifs\DI\Container;

class Server
{

    /**
     * @var \trifs\DI\Container
     */
    protected $di;

    /**
     * @param \trifs\DI\Container $di
     * @return void
     */
    public function __construct(Container $di)
    {
        $this->di = $di;
    }

    /**
     * @return void
     */
    public function run()
    {
        $successful = false;
        $route      = null;
        try {
            $route  = $this->di->request->getMatchingRoute($this->di->routes);
            $output = $this->executeRoute($route);
            $this->di->response->success($output);
            $successful = true;
        } catch (\Exception $e) {
            $this->di->log->warning(sprintf(
                'request failed with exception; code: %d, message: "%s"',
                $e->getCode(),
                $e->getMessage()
            ));
            $this->di->response->fail($e);
        }
    }

    /**
     * @param array $route [METHOD, ROUTE, CALLBACK]
     * @return mixed Result of a callback
     */
    protected function executeRoute(array $route)
    {
        $this->di->log->info(sprintf(
            'executing endpoint "%s"',
            $route[2]
        ));
        $this->validateRoute($route);
        $method   = $route[2];
        $callback = include sprintf('%s/%s.php', $this->getEndpointsFolder(), $method);
        return $callback($this->di);
    }

    /**
     * @param array $route [METHOD, ROUTE, CALLBACK]
     * @throws \trifs\DIServer\Request\InvalidRouteException
     * @return void
     */
    protected function validateRoute(array $route)
    {
        if (!isset($route[2])) {
            $this->di->log->error(sprintf(
                'route invalid "%s"',
                json_encode($route)
            ));
            throw new InvalidRouteException(sprintf(
                'Invalid route found (%s).',
                json_encode($route)
            ));
        }
    }

    /**
     * @return string
     */
    protected function getEndpointsFolder()
    {
        if (!isset($this->di->config['endpoints'])) {
            throw new Exception('configuration endpoints not defined');
        }

        return $this->di->config['endpoints'];
    }
}
