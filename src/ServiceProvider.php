<?php
namespace trifs\DIServer;

use \trifs\DIServer\Log;
use \trifs\DIServer\Log\ApiFormatter;
use \trifs\DIServer\Log\SyslogWriter;
use \trifs\DIServer\Request;
use \trifs\DIServer\Response;
use \trifs\DIServer\Server;
use \trifs\DI\Container;
use \trifs\DI\ServiceProviderInterface;

/**
 * Adds default set of DIServer's dependencies to DI
 */
class ServiceProvider implements ServiceProviderInterface
{
    /**
     * @var int
     */
    protected $idLength = 16;

    /**
     * @param \trifs\DI\Container
     * @return void
     */
    public function register(Container $app)
    {
        // add server
        $app->server = function ($app) {
            return new Server($app);
        };

        // add request
        // TODO: clean up
        $app->request = function ($app) {
            $requestUri    = $_SERVER['REQUEST_URI'];
            $requestMethod = $_SERVER['REQUEST_METHOD'];

            // parse request headers
            $requestHeaders = array_reduce(
                array_keys($_SERVER),
                function ($acc, $key) {
                    if (substr($key, 0, 5) === 'HTTP_') {
                        $cleanKey = str_replace(['HTTP_', '_'], ['', ' '], $key);
                        $cleanKey = str_replace(' ', '-', ucwords(strtolower($cleanKey)));
                        $acc[$cleanKey] = $_SERVER[$key];
                    }
                    return $acc;
                },
                []
            );

            return new Request(
                $requestMethod,
                $requestUri,
                file_get_contents('php://input'),
                $_GET ?: [],
                $requestHeaders
            );
        };

        // add response
        $app->response = function ($app) {
            return new Response;
        };

        // add log
        $app->log = function ($app) {
            return new Log(
                new ApiFormatter($app),
                new SyslogWriter($app),
                LOG_DEBUG
            );
        };

        // add requestId
        $app->register(new RequestIdServiceProvider);
    }
}
