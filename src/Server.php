<?php
namespace trifs\DIServer;

use \trifs\DIServer\Request\InvalidRouteException;
use \trifs\DI\Container;

class Server
{

    /**
     * @var \trifs\DI\Container
     */
    protected $app;

    /**
     * @param \trifs\DI\Container $app
     * @return void
     */
    public function __construct(Container $app)
    {
        $this->app = $app;
    }

    /**
     * @return void
     */
    public function run()
    {
        $successful = false;
        $route      = null;
        try {
            $route  = $this->app->request->getMatchingRoute($this->app->routes);
            $output = $this->executeRoute($route);
            $this->app->response->success($output);
            $successful = true;
        } catch (\Exception $e) {
            $this->app->log->warning(sprintf(
                'request failed with exception; code: %d, message: "%s"',
                $e->getCode(),
                $e->getMessage()
            ));
            $this->app->response->fail($e);
        }
    }

    /**
     * @param array $route [METHOD, ROUTE, CALLBACK]
     * @return mixed Result of a callback
     */
    protected function executeRoute(array $route)
    {
        $this->app->log->info(sprintf(
            'executing endpoint "%s"',
            $route[2]
        ));
        $this->validateRoute($route);
        $method   = $route[2];
        $callback = include sprintf('%s/%s.php', $this->getEndpointsFolder(), $method);
        return $callback($this->app);
    }

    /**
     * @param array $route [METHOD, ROUTE, CALLBACK]
     * @throws \trifs\DIServer\Request\InvalidRouteException
     * @return void
     */
    protected function validateRoute(array $route)
    {
        if (!isset($route[2])) {
            $this->app->log->error(sprintf(
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
        if (!isset($this->app->config['endpoints'])) {
            throw new Exception('configuration endpoints not defined');
        }

        return $this->app->config['endpoints'];
    }
}
