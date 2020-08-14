<?php

use Illuminate\Support\Facades\Storage;

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
const ShipmentPending = 'pending';
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

const AuthSeller = 'auth:seller-api';
const AuthCustomer = 'auth:customer-api';
const AuthAdmin = 'auth:admin';

/**
 * App is in production stage and is deployed to production servers.
 */
const AppEnvironmentProduction = 'production';

/**
 * App is in development and is only available on development machines and test servers.
 */
const AppEnvironmentLocal = 'local';

function __status($status)
{
    if ($status == 1)
        return 'Active';
    else if ($status == 0)
        return 'Disabled';
    else
        return 'Unknown';
}

function __visibility($visibility)
{
    if ($visibility == 1)
        return "Visible";
    else if ($visibility == 2)
        return "Hidden";
    else
        return "Unknown";
}

function __blank($value)
{
    $value = trim($value);
    if ($value == null || strlen($value) < 1)
        return '-';
    else
        return $value;
}

function __rating($value)
{
    if ($value == 0)
        return '<Not rated>';
    else
        return $value;
}

function __ellipsis($value, $length = 20)
{
    return strlen($value) > $length ? substr($value, 0, $length) . "..." : $value;
}

function __boolean($value)
{
    $value = boolval($value);
    return $value == true ? 'Yes' : 'No';
}

function null($expression)
{
    return $expression == null;
}

function __modelNameFromSlug($slug)
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

function image($path = null)
{
    if ($path == null)
        return null;
    else
        return Storage::download($path);
}

/**
 * @param $payload
 * @return false|string
 */
function jsonEncode($payload)
{
    $encoded = json_encode($payload);
    return $encoded == false ? '' : $encoded;
}

/**
 * @param $payload
 * @return mixed
 */
function jsonDecode($payload)
{
    $decoded = json_decode($payload);
    return $decoded;
}

/**
 * @param $payload
 * @return array
 */
function jsonDecodeArray($payload)
{
    $decoded = json_decode($payload, true);
    return $decoded == null ? [] : $decoded;
}

/**
 * @param string $route
 * @return \App\Classes\WebResponse
 */
function responseWeb()
{
    return \App\Classes\WebResponse::instance();
}

function responseApp()
{
    return \App\Classes\Builders\ResponseBuilder::instance();
}

function iterate($arrayable, $callback)
{
    collect($arrayable)->each($callback);
}

function hostName()
{
    return parse_url(env('APP_URL'), PHP_URL_HOST);
}

function subDomain(string $prefix)
{
    return sprintf('%s.%s', $prefix, hostName());
}

function __cast($value, $type)
{
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
function appEnvironment($match = null): ?string
{
    if ($match == null)
        return env('APP_ENV');
    else
        return \App\Classes\Str::equals($match, env('APP_ENV'));
}

function countRequiredPages(int $total, int $perPage)
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

function makeUrl($path): ?string
{
    return \App\Storage\SecuredDisk::existsUrl($path);
}

function slack($message)
{
    \Illuminate\Support\Facades\Log::channel('slack')->info($message);
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
function random_str(int $length = 16, string $keyspace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'): string
{
    $pieces = [];
    $max = mb_strlen($keyspace, '8bit') - 1;
    for ($i = 0; $i < $length; ++$i) {
        $pieces [] = $keyspace[random_int(0, $max)];
    }
    return implode('', $pieces);
}