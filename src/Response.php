<?php
namespace trifs\DIServer;

class Response
{

    /**
     * @param \Exception $e
     * @return void
     */
    public function fail(\Exception $exception)
    {
        $httpCode = Http::CODE_INTERNAL_ERROR;

        if ($exception instanceof \trifs\DIServer\Exception) {
            $httpCode = $exception->getCode();
        }

        $this->setHeaders($httpCode);

        $this->output([
            'error' => $exception->getMessage(),
            // 'trace' => $exception->getTrace(),
        ]);
    }

    /**
     * @param mixed data
     * @return void
     */
    public function success($data)
    {
        $this->validateResponseData($data);
        $this->setHeaders($data['code']);
        $this->output($data['data'] ?: '');
    }

    /**
     * @param  mixed $data
     * @return void
     */
    protected function output($data)
    {
        $output = json_encode($data);

        if ($output === false) {
            $this->setHeaders(500);
            echo sprintf(
                '{"error": "encoding failed with \"%s\""}',
                addslashes(json_last_error_msg())
            );
        } else {
            echo $output;
        }
    }

    /**
     * @param uint $httpCode
     * @return void
     */
    protected function setHeaders($httpCode)
    {
        if ($this->canSetHeaders()) {
            http_response_code($httpCode);
            header('Content-Type: application/json; charset=UTF-8');
        }
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
        if (!is_array($responseData) ||
            !isset($responseData['code'])) {
            throw new Response\InvalidResponseException('Invalid response received');
        }
    }
}
