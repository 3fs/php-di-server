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
    public function register(Container $di)
    {
        // add server
        $di->server = function ($di) {
            return new Server($di);
        };

        // add request
        // TODO: clean up
        $di->request = function ($di) {
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
        $di->response = function ($di) {
            return new Response;
        };

        // add log
        $di->log = function ($di) {
            return new Log(
                new ApiFormatter($di),
                new SyslogWriter($di),
                LOG_DEBUG
            );
        };

        // add requestId
        $di->register(new RequestIdServiceProvider);
    }
}
