<?php

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