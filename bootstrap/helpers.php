<?php

use Illuminate\Support\Facades\Storage;

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