<?php
namespace trifs\DIServer;

class Request
{

    /**
     * @var string
     */
    protected $method;

    /**
     * @var string
     */
    protected $uri;

    /**
     * @var array
     */
    protected $data = [];

    /**
     * @var array
     */
    protected $headers = [];

    /**
     * @param string      $requestMethod GET|POST|...
     * @param string      $uri           request uri
     * @param string|void $rawData       raw request data (json string)
     * @param array|void  $parameters    request parameters
     * @param array|void  $headers       request headers
     * @return void
     */
    public function __construct($requestMethod, $uri, $rawData = '', array $parameters = [], array $headers = [])
    {
        $this->method  = $requestMethod;
        $this->uri     = $this->normalizeUri($uri);
        $this->data    = array_merge($this->processRawData($rawData), $parameters);
        $this->headers = $headers;
    }

    /**
     * Fetches a parameter
     * @param string          $parameter    Name of the parameter
     * @param mixed|null|void $defaultValue Optional default value
     * @return mixed|null
     */
    public function get($parameter, $defaultValue = null)
    {
        if (isset($this->data[$parameter])) {
            return $this->data[$parameter];
        }
        return $defaultValue;
    }

    /**
     * Fetches a parameter and throws an exception if it's not found
     * @param string $parameterName
     * @param mixed|null|void $defaultValue
     * @throw \trifs\DIServer\Request\ParameterNotFoundException
     * @return mixed
     */
    public function getRequired($parameterName, $defaultValue = null)
    {
        $value = $this->get($parameterName) ?: $defaultValue;
        if (is_null($value)) {
            throw new \trifs\DIServer\Request\ParameterNotFoundException(sprintf(
                'Parameter %s is not set.',
                $parameterName
            ));
        }
        return $value;
    }

    /**
     * @return string
     */
    public function getUri()
    {
        return $this->uri;
    }

    /**
     * @param  string         $headerName
     * @return string|boolean
     */
    public function getHeader($headerName)
    {
        return isset($this->headers[$headerName]) ? $this->headers[$headerName] : false;
    }

    /**
     * @return int|null
     */
    public function getRequestUser()
    {
        $headerValue = $this->getHeader('X-Token');
        return $headerValue === false ? null : (int) $headerValue;
    }

    /**
     * @param array $routes list of route definitions
     * @throws \trifs\DIServer\Response\RouteNotFoundException
     * @return array|null
     */
    public function getMatchingRoute(array $routes)
    {
        $result = null;

        foreach ($routes as $route) {
            list($method, $uri) = $route;

            if ($this->isMethodMatching($method) && $this->isUriMatching($uri)) {
                $result = $route;
                break;
            }
        }

        if (is_null($result)) {
            throw new \trifs\DIServer\Request\RouteNotFoundException(sprintf(
                'Endpoint %s::%s is not defined',
                $this->method,
                $this->uri
            ));
        };

        return $result;
    }

    /**
     * Normalizes the uri (removes ?...)
     * @param string $uri
     * @return string
     */
    protected function normalizeUri($uri)
    {
        return explode('?', $uri)[0];
    }

    /**
     * @param string $rawData
     * @return array
     */
    protected function processRawData($rawData)
    {
        return json_decode($rawData, true) ?: [];
    }

    /**
     * @param string $method
     * @return bool
     */
    protected function isMethodMatching($method)
    {
        return $method === $this->method;
    }

    /**
     * @param string $uri
     * @return bool
     */
    protected function isUriMatching($uri)
    {
        if ($uri === $this->uri) {
            return true;
        }

        if (strpos($uri, '{') !== false) {
            $pattern = str_replace(
                ['{$', '}', '/'],
                ['(?<', '>[\w-]+)', '\/'],
                $uri
            );

            if (preg_match('/^' . $pattern . '$/', $this->uri, $matches)) {
                $parameters = [];
                array_walk($matches, function ($value, $key) use (&$parameters) {
                    if (!is_integer($key)) {
                        $parameters[$key] = $value;
                    }
                });

                $this->data = array_merge($parameters, $this->data);

                return true;
            }
        }

        return false;
    }
}
