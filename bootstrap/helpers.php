<?php

use App\Classes\Builders\ResponseBuilder;
use App\Classes\WebResponse;
use Illuminate\Support\Facades\Log;

const HttpOkay = 200;

const HttpCreated = 201;

const HttpNoContent = 204;

const HttpInvalidRequestFormat = 400;

const HttpResourceNotFound = 404;

const HttpServerError = 500;

const HttpResourceAlreadyExists = 409;

const HttpUnauthorized = 401;

const HttpDeniedAccess = 403;

const HttpNotModified = 304;

const AUTH_SELLER_API = 'auth:seller-api';
const AUTH_CUSTOMER_API = 'auth:customer-api';

/**
 * App is in production stage and is deployed to production servers.
 */
const AppEnvironmentProduction = 'production';

/**
 * App is in development and is only available on development machines and test servers.
 */
const AppEnvironmentLocal = 'local';

function __modelNameFromSlug ($slug)
{
	$identifier = 'model-mapping.' . $slug;
	$modelName = __($identifier);
	if (strcmp($modelName, $identifier) == 0) {
		$name = $slug;
		$lastIndex = strrpos($name, "\\") + 1;
		$modelName = substr($name, $lastIndex);
	}
	return $modelName;
}

/**
 * @param $payload
 * @return false|string
 */
function jsonEncode ($payload)
{
	$encoded = json_encode($payload);
	return $encoded == false ? '' : $encoded;
}

/**
 * @param $payload
 * @return mixed
 */
function jsonDecode ($payload)
{
	$decoded = json_decode($payload);
	return $decoded;
}

/**
 * @param $payload
 * @return array
 */
function jsonDecodeArray ($payload)
{
	$decoded = json_decode($payload, true);
	return $decoded == null ? [] : $decoded;
}

/**
 * @return WebResponse
 */
function responseWeb () : WebResponse
{
	return WebResponse::instance();
}

function responseApp () : ResponseBuilder
{
	return ResponseBuilder::instance();
}

/**
 * Returns current app environment or matches it with the given string.
 * @param null $match Env string to match against
 * @return string|null
 */
function appEnvironment ($match = null) : ?string
{
	if ($match == null)
		return env('APP_ENV');
	else
		return \App\Classes\Str::equals($match, env('APP_ENV'));
}

function countRequiredPages (int $total, int $perPage)
{
	if ($total <= $perPage)
		return 1;

	$result = $total / $perPage;
	$remainder = $total % $perPage;
	if ($remainder > 0 && $remainder <= $perPage) {
		$result += 1;
	}
	return $result;
}

function makeUrl ($path) : ?string
{
	return \App\Storage\SecuredDisk::existsUrl($path);
}

function slack ($message)
{
	Log::channel('slack')->info($message);
}

/**
 * Generate a random string, using a cryptographically secure
 * pseudorandom number generator (random_int)
 *
 * This function uses type hints now (PHP 7+ only), but it was originally
 * written for PHP 5 as well.
 *
 * For PHP 7, random_int is a PHP core function
 * For PHP 5.x, depends on https://github.com/paragonie/random_compat
 *
 * @param int $length How many characters do we want?
 * @param string $keyspace A string of all possible characters
 *                         to select from
 * @return string
 * @throws Exception
 */
function random_str (int $length = 16, string $keyspace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ') : string
{
	$pieces = [];
	$max = mb_strlen($keyspace, '8bit') - 1;
	for ($i = 0; $i < $length; ++$i) {
		$pieces [] = $keyspace[random_int(0, $max)];
	}
	return implode('', $pieces);
}