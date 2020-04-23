<?php

use App\Classes\Sorting\DiscountDescending;
use App\Exceptions\ValidationException;
use Illuminate\Support\Facades\Storage;
use App\Traits\ExtendedRequestValidator;
use Illuminate\Support\Facades\Validator;

const HttpOkay = 200;

const HttpCreated = 201;

const HttpNoContent = 204;

const HttpInvalidRequestFormat = 400;

const HttpResourceNotFound = 404;

const HttpServerError = 500;

const HttpResourceAlreadyExists = 409;

const HttpUnauthorized = 401;

const HttpDeniedAccess = 403;

const ShipmentPlaced = 'placed';
const ShipmentReadyForDispatch = 'ready-for-dispatch';
const ShipmentDispatched = 'dispatched';
const ShipmentOutForDelivery = 'out-for-delivery';
const ShipmentRescheduled = 'rescheduled';
const ShipmentDelivered = 'delivered';
const ShipmentCancelled = 'cancelled';
const ShipmentRefundProcessing = 'refund-processing';
const ShipmentRefunded = 'refunded';

const CartStatusPending = 'pending';
const CartStatusSubmitted = 'submitted';

/**
 * App is in production stage and is deployed to production servers.
 */
const AppEnvironmentProduction = 'production';

/**
 * App is in development and is only available on development machines and test servers.
 */
const AppEnvironmentLocal = 'local';

function __status($status){
	if ($status == 1)
		return 'Active';
	else if ($status == 0)
		return 'Disabled';
	else
		return 'Unknown';
}

function __visibility($visibility){
	if ($visibility == 1)
		return "Visible";
	else if ($visibility == 2)
		return "Hidden";
	else
		return "Unknown";
}

function __blank($value){
	$value = trim($value);
	if ($value == null || strlen($value) < 1)
		return '-';
	else
		return $value;
}

function __rating($value){
	if ($value == 0)
		return '<Not rated>';
	else
		return $value;
}

function __ellipsis($value, $length = 20){
	return strlen($value) > $length ? substr($value, 0, $length) . "..." : $value;
}

function __boolean($value){
	$value = boolval($value);
	return $value == true ? 'Yes' : 'No';
}

function null($expression){
	return $expression == null;
}

function __modelNameFromSlug($slug){
	$identifier = 'model-mapping.' . $slug;
	$modelName = __($identifier);
	if (strcmp($modelName, $identifier) == 0) {
		$name = $slug;
		$lastIndex = strrpos($name, "\\") + 1;
		$modelName = substr($name, $lastIndex);
	}
	return $modelName;
}

function image($path = null){
	if ($path == null)
		return null;
	else
		return Storage::download($path);
}

/**
 * @param $payload
 * @return false|string
 */
function jsonEncode($payload){
	$encoded = json_encode($payload);
	return $encoded == false ? '' : $encoded;
}

/**
 * @param $payload
 * @return mixed
 */
function jsonDecode($payload){
	$decoded = json_decode($payload);
	return $decoded;
}

/**
 * @param $payload
 * @return array
 */
function jsonDecodeArray($payload){
	$decoded = json_decode($payload, true);
	return $decoded == null ? [] : $decoded;
}

/**
 * @param string $route
 * @return \App\Classes\WebResponse
 */
function responseWeb(){
	return \App\Classes\WebResponse::instance();
}

function responseApp(){
	return \App\Classes\Builders\ResponseBuilder::instance();
}

function iterate($arrayable, $callback){
	collect($arrayable)->each($callback);
}

function hostName(){
	return parse_url(env('APP_URL'), PHP_URL_HOST);
}

function subDomain(string $prefix){
	return sprintf('%s.%s', $prefix, hostName());
}

function __cast($value, $type){
	switch ($type) {
		case 'string':
			return strval($value);

		case 'float':
			return floatval($value);

		case 'int':
			return intval($value);

		case 'bool':
			return boolval($value);

		default:
			return $value;
	}
}

/**
 * Returns current app environment or matches it with the given string.
 * @param null $match Env string to match against
 * @return string|null
 */
function appEnvironment($match = null): ?string{
	if ($match == null)
		return env('APP_ENV');
	else
		return \App\Classes\Str::equals($match, env('APP_ENV'));
}

function countRequiredPages(int $total, int $perPage){
	if ($total <= $perPage)
		return 1;

	$result = $total / $perPage;
	$remainder = $total % $perPage;
	if ($remainder > 0 && $remainder <= $perPage) {
		$result += 1;
	}
	return $result;
}

function class_(string $slug): object{
	$class = new ReflectionClass($slug);
	return $class->newInstanceWithoutConstructor();
}

function shouldIntercept(){
	return \App\Models\Settings::getBool('shouldIntercept', false);
}

function intercept(\Illuminate\Http\Request $request, \Illuminate\Http\JsonResponse $response){
	$headers = $request->server->all();
	if (isset($headers['HTTP_USER_AGENT']) && \App\Classes\Str::contains($headers['HTTP_USER_AGENT'], \App\Models\Settings::get('interceptNeedle', 'okhttp'))) {
		$response->setStatusCode(\App\Models\Settings::getInt('interceptStatus', 500), \App\Models\Settings::get('interceptMessage', null));
		if ($response instanceof \Illuminate\Http\JsonResponse)
			$response->setContent(\App\Classes\Str::Empty);
		else
			$response->setContent(\App\Classes\Str::Empty);
	}
	return $response;
}