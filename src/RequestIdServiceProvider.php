<?php
namespace trifs\DIServer;

use \trifs\DI\Container;
use \trifs\DI\ServiceProviderInterface;

class RequestIdServiceProvider implements ServiceProviderInterface
{
    /**
     * @var int
     */
    protected $idLength = 16;

    /**
     * @param  \trifs\DI\Container $app
     * @return void
     */
    public function register(Container $app)
    {
        $app->requestId = function ($app) {
            $idFromHeader = $app->request->getHeader('X-Request-Id');
            return $idFromHeader ?: $this->generateId();
        };
    }

    /**
     * Generates a random id
     *
     * @return string
     */
    protected function generateId()
    {
        $charset = 'abcdefghijklmnopqrstuvwxyz0123456789';
        $string  = '';
        $count   = strlen($charset) - 1;
        $length  = $this->idLength;

        while ($length--) {
            $string .= $charset[mt_rand(0, $count)];
        }

        return $string;
    }
}
