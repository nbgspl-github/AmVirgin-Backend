<?php
namespace App\Http\Controllers\Traits;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

trait ResponseTrait
{
    /**
     * Status code
     * @var integer
     */
    protected $statusCode = Response::HTTP_OK;

    /**
     * Headers
     * @var array
     */
    protected $headers;

    /**
     * Options
     * @var array
     */
    protected $options;

    /**
     * Sets the status code
     *
     * @param int $statusCode
     * @return $this
     */
    public function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;
        return $this;
    }

    /**
     * Returns the status code property
     *
     * @return int
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * Builds the response array with status code and returns a response
     *
     * @param  array|string $data
     * @param  array $meta
     * @param  array $headers
     * @return \Illuminate\Http\Response
     */
    public function response($data, array $meta = array(), $headers = [])
    {
        return response($this->buildResponseBody($data, $meta), $this->getStatusCode(), $headers);
    }

    /**
     * Builds the response body. Meta will be ommited if it doesn't exist. SyncMarker will be included if it wa set
     * @param  array|string $data
     * @param  array $meta
     * @return array
     */
    public function buildResponseBody($data, array $meta)
    {
        $body = [];
        // $body['data'] = $data;
        // if (!empty($meta)) {
        //     $body['meta'] = $meta;
        // }

        return $data;
    }

    /**
     * Sends back an empty response with a status code of no content.
     *
     * @return \Illuminate\Http\Response
     */
    public function responseNoContent()
    {
        $this->setStatusCode(Response::HTTP_NO_CONTENT);
        return response('', $this->getStatusCode());
    }

    /**
     * Sets the Created status code and returns a response
     *
     * @param  array $data
     * @return \Illuminate\Http\Response
     */
    public function responseCreated($data=[])
    {
        return $this->setStatusCode(Response::HTTP_CREATED)->response($data);
    }

    /**
     * Sets the Created status code and returns a response
     *
     * @param  array $data
     * @return \Illuminate\Http\Response
     */
    public function responseOkay($data)
    {
        return $this->setStatusCode(Response::HTTP_OK)->response($data);
    }

    /**
     * Builds an error response
     *
     * @param string $msg
     * @param string $errorCode
     * @return \Illuminate\Http\Response
     */
    public function error($msg = "Error Happened", $errorCode = "TERRR")
    {
        return response([
            'error' => [
                'status' => 0,
                'message' => $msg,
                'code' => $errorCode,
            ]
        ], $this->getStatusCode());
    }

    /**
     * Sets the Bad Request (400) status code and returns a response
     *
     * @param string $msg
     * @param string $errorCode
     * @return \Illuminate\Http\Response
     */
    public function errorBadRequest($msg = 'Bad Request', $errorCode = 'TERBR')
    {
        return $this->setStatusCode(Response::HTTP_BAD_REQUEST)->error($msg, $errorCode);
    }

    /**
     * Sets the Not Modified (304) status code and returns a response
     *
     * @param string $msg
     * @param string $errorCode
     * @return \Illuminate\Http\Response
     */
    public function errorNotModified($msg = 'Not Modified', $errorCode = 'TERFB')
    {
        return $this->setStatusCode(Response::HTTP_NOT_MODIFIED)->error($msg, $errorCode);
    }

    /**
     * Sets the Forbidden (403) status code and returns a response
     *
     * @param string $msg
     * @param string $errorCode
     * @return \Illuminate\Http\Response
     */
    public function errorForbidden($msg = 'Forbidden', $errorCode = 'TERFB')
    {
        return $this->setStatusCode(Response::HTTP_FORBIDDEN)->error($msg, $errorCode);
    }

    /**
     * Sets the Not Found (404) status code and returns a response
     *
     * @param string $msg
     * @param string $errorCode
     * @return \Illuminate\Http\Response
     */
    public function errorNotFound($msg = 'Not found', $errorCode = 'TERNF')
    {
        return $this->setStatusCode(Response::HTTP_NOT_FOUND)->error($msg, $errorCode);
    }

    /**
     * Sets the Method Not Allowed (405) status code and returns a response
     *
     * @param string $msg
     * @param string $errorCode
     * @return \Illuminate\Http\Response
     */
    public function errorMethodNotAllowed($msg = 'Method not allowed', $errorCode = 'TERME')
    {
        return $this->setStatusCode(Response::HTTP_METHOD_NOT_ALLOWED)->error($msg, $errorCode);
    }

    /**
     * Sets the Internal Server (500) status code and returns a response
     *
     * @param string $msg
     * @param string $errorCode
     * @return \Illuminate\Http\Response
     */
    public function errorInternalServer($msg = 'An unknown error occured', $errorCode = 'TERIN')
    {
        return $this->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR)->error($msg, $errorCode);
    }

    /**
     * Sets the Unprocessable Entity (422) status code and returns a response
     *
     * @param string $msg
     * @param string $errorCode
     * @return \Illuminate\Http\Response
     */
    public function errorUnprocessabeEntity($msg = 'Unprocessable Entity', $errorCode = 'TERUN')
    {
        return $this->setStatusCode(Response::HTTP_UNPROCESSABLE_ENTITY)->error($msg, $errorCode);
    }

    /**
     * Sets the Entity Already Exists (409) status code and returns a response
     * @param string $msg
     * @param string $errorCode
     * @return \Illuminate\Http\Response
     */
    public function errorEntityAlreadyExists($msg = 'Entity already exists', $errorCode = 'TEAE')
    {
        return $this->setStatusCode(Response::HTTP_CONFLICT)->error($msg, $errorCode);
    }
    /**
     * Sets the Unauthorized (401) status code and returns a response
     *
     * @param string $msg
     * @param string $errorCode
     * @return \Illuminate\Http\Response
     */
    public function errorUnauthorized($msg = 'Unauthorized', $errorCode = 'TAUTH')
    {
        return $this->setStatusCode(Response::HTTP_UNAUTHORIZED)->error($msg, $errorCode);
    }

    /**
     * Return a new streamed response from the application.
     *
     * @param  \Closure  $callback
     * @param  array     $headers
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function stream($callback, array $headers = [])
    {
        return new StreamedResponse(
            $callback,
            $this->getStatusCode(),
            $headers
        );
    }
}
