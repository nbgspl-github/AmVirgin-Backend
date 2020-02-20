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

function __status($status) {
	if ($status == 1)
		return 'Active';
	else if ($status == 0)
		return 'Disabled';
	else
		return 'Unknown';
}

function __visibility($visibility) {
	if ($visibility == 1)
		return "Visible";
	else if ($visibility == 2)
		return "Hidden";
	else
		return "Unknown";
}

function __blank($value) {
	$value = trim($value);
	if ($value == null || strlen($value) < 1)
		return '-';
	else
		return $value;
}

function __rating($value) {
	if ($value == 0)
		return '<Not rated>';
	else
		return $value;
}

function __ellipsis($value, $length = 20) {
	return strlen($value) > $length ? substr($value, 0, $length) . "..." : $value;
}

function __boolean($value) {
	$value = boolval($value);
	return $value == true ? 'Yes' : 'No';
}

function null($expression) {
	return $expression == null;
}

function __modelNameFromSlug($slug) {
	$identifier = 'model-mapping.' . $slug;
	$modelName = __($identifier);
	if (strcmp($modelName, $identifier) == 0) {
		$name = $slug;
		$lastIndex = strrpos($name, "\\") + 1;
		$modelName = substr($name, $lastIndex);
	}
	return $modelName;
}

function image($path = null) {
	if ($path == null)
		return null;
	else
		return Storage::download($path);
}

/**
 * @param $payload
 * @return false|string
 */
function jsonEncode($payload) {
	$encoded = json_encode($payload);
	return $encoded == false ? '' : $encoded;
}

/**
 * @param $payload
 * @return mixed
 */
function jsonDecode($payload) {
	$decoded = json_decode($payload);
	return $decoded;
}

/**
 * @param $payload
 * @return array
 */
function jsonDecodeArray($payload) {
	$decoded = json_decode($payload, true);
	return $decoded == null ? [] : $decoded;
}

/**
 * @param string $route
 * @return \App\Classes\WebResponse
 */
function responseWeb() {
	return \App\Classes\WebResponse::instance();
}

function responseApp() {
	return \App\Classes\Builders\ResponseBuilder::instance();
}

function iterate($arrayable, $callback) {
	collect($arrayable)->each($callback);
}