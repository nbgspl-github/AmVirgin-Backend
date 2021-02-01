<?php

use App\Library\Http\AppResponse;
use App\Library\Http\WebResponse;
use App\Library\Utils\Extensions\Str;
use Illuminate\Support\Facades\Log;

const AUTH_SELLER = 'auth:seller-api';
const AUTH_CUSTOMER = 'auth:customer-api';
const AUTH_ADMIN = 'auth:admin';

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
	return json_decode($payload);
}

/**
 * @param $payload
 * @return array
 */
function jsonDecodeArray ($payload) : array
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

function responseApp () : AppResponse
{
	return AppResponse::instance();
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
		return Str::equals($match, env('APP_ENV'));
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
	return \App\Library\Utils\Uploads::existsUrl($path);
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

/**
 * Generate a querystring url for the application.
 *
 * Assumes that you want a URL with a querystring rather than route params
 * (which is what the default url() helper does)
 *
 * @param string $path
 * @param mixed $qs
 * @param bool $secure
 * @return string
 */
function qs_url ($path = null, $qs = array(), $secure = null) : string
{
	$url = app('url')->to($path, $secure);
	if (count($qs)) {

		foreach ($qs as $key => $value) {
			$qs[$key] = sprintf('%s=%s', $key, urlencode($value));
		}
		$url = sprintf('%s?%s', $url, implode('&', $qs));
	}
	return $url;
}

function is_even ($number) : bool
{
	return $number % 2 == 0;
}

function __active (string $route) : string
{
	if (request()->is($route))
		return 'class="active"';
	else
		return "";
}