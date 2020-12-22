<?php
/**
 * Copyright (c) 2019. Aviral Singh
 */

namespace App\Library\Http\Response;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;

/**
 * Builder class to create responses, supports chained method class for easy one line responses.
 * @package App\Utils
 */
class AppResponse
{

	/**
	 * @var bool
	 */
	private static $loggingEnabled = true;
	/**
	 * @var string
	 */
	private $message;
	/**
	 * @var int
	 */
	private $status = 0;
	/**
	 * @var array
	 */
	private $items = [];
	/**
	 * @var int
	 */
	private $statusCode = 200;
	/**
	 * @var int
	 */
	private $requestId = -1;

	/**
	 * Enables response logging for each response generated through build method.
	 * @param bool $enable
	 */
	public static function enableResponseLogging (bool $enable)
	{
		self::$loggingEnabled = $enable;
	}

	/**
	 * Returns an instance of AppResponse setting its status to 1.
	 * @return AppResponse
	 */
	public static function asSuccess ()
	{
		$object = new AppResponse();
		$object->message = 'Success';
		$object->status = 1;
		return $object;
	}

	/**
	 * Returns an instance of AppResponse setting its status to 0.
	 * @return AppResponse
	 */
	public static function asFailure ()
	{
		$object = new AppResponse();
		$object->message = 'Failed';
		$object->status = 0;
		return $object;
	}

	/**
	 * Returns an instance of AppResponse setting its status to 0.
	 * @return AppResponse
	 */
	public static function asError ()
	{
		$object = new AppResponse();
		$object->message = 'Failed';
		$object->status = 0;
		$object->statusCode = \Illuminate\Http\Response::HTTP_INTERNAL_SERVER_ERROR;
		return $object;
	}

	public static function instance ()
	{
		return new AppResponse();
	}

	/**
	 * Sets the message field for this response.
	 * @param $message string|callable|Validator
	 * @return AppResponse
	 */
	public function message ($message)
	{
		if (is_callable($message)) {
			$this->message = call_user_func($message);
		} else if ($message instanceof Validator) {
			$this->message = $message->errors()->first();
		} else {
			$this->message = $message;
		}
		return $this;
	}

	/**
	 * Adds a custom key-value pair to response data.
	 * @param string $key
	 * @param null $value
	 * @return $this
	 */
	public function setValue (string $key, $value = null)
	{
		$this->items[$key] = $value;
		return $this;
	}

	/**
	 * Adds a payload array/object/Collection under payload key.
	 * @param null $payload
	 * @return $this
	 */
	public function payload ($payload = null)
	{
		$this->items['payload'] = $payload;
		return $this;
	}

	public function setResource (JsonResource $resource, string $key = 'data', Request $request = null)
	{
		$this->items[$key] = $resource->toArray($request);
		return $this;
	}

	/**
	 * Adds an empty list to response data.
	 * @param string $key Name of list (default is list)
	 * @return $this
	 */
	public function setEmptyList (string $key = 'list')
	{
		$this->items[$key] = [];
		return $this;
	}

	/**
	 * Sets a HTTP status code for the response.
	 * @param int $statusCode
	 * @return $this
	 */
	public function status (int $statusCode)
	{
		$this->statusCode = $statusCode;
		return $this;
	}

	/**
	 * Builds an returns a JsonResponse with the specified response data.
	 * @return JsonResponse
	 */
	public function send ()
	{
		$response = [];
		$response['status'] = $this->statusCode;
		if (isset($this->message) && strlen($this->message) > 0)
			$response['message'] = $this->message;
		foreach ($this->items as $key => $value) {
			$response[$key] = $value;
		}
		return response()->json($response);
	}

	/**
	 * Builds an returns a JsonResponse with the specified response data.
	 * @return array
	 */
	public function buildArray ()
	{
		$response = [];
		$response['status'] = $this->status;
		$response['message'] = $this->message;
		foreach ($this->items as $key => $value) {
			$response[$key] = $value;
		}
		return $response;
	}

	public function prepare ($payload, $status = Response::HTTP_OK, $message = null) : JsonResponse
	{
		if (empty($message))
			return self::payload($payload)->status($status)->send();
		else
			return self::payload($payload)->status($status)->message($message)->send();
	}
}