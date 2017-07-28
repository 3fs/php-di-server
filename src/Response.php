<?php
namespace trifs\DIServer;

class Response
{

    /**
     * @param  \Exception $e
     * @return void
     */
    public function fail(\Exception $exception)
    {
        $httpCode = Http::CODE_INTERNAL_ERROR;

        if ($exception instanceof \trifs\DIServer\Exception) {
            $httpCode = $exception->getCode();
        }

        $this->setHeaders(['code' => $httpCode]);
        $this->output(['error' => $exception->getMessage()], []);
    }

    /**
     * @param  array data
     * @return void
     */
    public function success(array $data)
    {
        $this->validateResponseData($data);
        $this->setHeaders($data);
        $this->output(isset($data['data']) ? $data['data']: '', $data);
    }

    /**
     * @param  string $redirectTo
     * @return array
     */
    public function redirect($redirectTo)
    {
        if ($this->canSetHeaders()) {
            header(sprintf('Location: %s', $redirectTo));
        }
        return [
            'code' => Http::CODE_FOUND,
            'data' => '',
        ];
    }

    /**
     * @param  mixed $content
     * @param  array $data
     * @return void
     */
    protected function output($content, array $data)
    {
        if ($this->getContentType($data) === Http::CONTENT_TYPE_JSON) {
            $output = json_encode($content);

            if ($output === false) {
                $this->setHeaders(['code' => Http::CODE_INTERNAL_ERROR]);
                echo sprintf(
                    '{"error": "encoding failed with \"%s\""}',
                    addslashes(json_last_error_msg())
                );
            }
        } else {
            $output = $content;
        }

        echo $output;
    }

    /**
     * @param  array $data
     * @return void
     */
    protected function setHeaders(array $data)
    {
        if ($this->canSetHeaders()) {
            http_response_code($this->getCode($data));
            header(sprintf('Content-Type: %s; charset=UTF-8', $this->getContentType($data)));
        }
    }

    /**
     * Returns the HTTP code defined in array (`code`) or returns the default code.
     *
     * @param  array $data
     * @return uint
     */
    protected function getCode(array $data)
    {
        return isset($data['code']) ? (int) $data['code'] : Http::CODE_OK;
    }

    /**
     * Returns content type defined in $data array. Retrurns default content type if key is not
     * defined
     *
     * @param  array $data
     * @return string
     */
    protected function getContentType(array $data)
    {
        return isset($data['contentType']) ? $data['contentType'] : Http::CONTENT_TYPE_JSON;
    }

    /**
     * @return boolean
     */
    protected function canSetHeaders()
    {
        return PHP_SAPI !== 'cli';
    }

    /**
     * @param array $responseData
     * @throws \trifs\DIServer\Response\InvalidResponseException
     * @return void
     */
    protected function validateResponseData($responseData)
    {
        if (!is_array($responseData)) {
            throw new Response\InvalidResponseException('Invalid response received');
        }
    }
}
