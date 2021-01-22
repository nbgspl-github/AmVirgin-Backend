<?php

return [

	/*
	|--------------------------------------------------------------------------
	| Authentication Language Lines
	|--------------------------------------------------------------------------
	|
	| The following language lines are used during authentication for various
	| messages that we need to display to the user. You are free to modify
	| these language lines according to your application's requirements.
	|
	*/

	'failed' => 'These credentials do not match our records.',
	'throttle' => 'Too many login attempts. Please try again in :seconds seconds.',
	'otp' => [
		'message' => "Your one time password for authentication is :otp.",
		'guest' => "We've send a one time password to complete registration.",
		'login' => "Otp has been sent to your registered mobile number.",
		"failed_verification" => "Your given one time password is invalid."
	],
	'user' => [
		'found' => "User found for that key.",
		"not_found" => "User not found for that key.",
		"restricted" => "Your account is temporarily inactive. Please try again in a while.",
		"invalid_credentials" => "Your given credentials are invalid."
	],
	'login' => [
		'success' => "You have been logged in successfully."
	],
	'logout' => [
		'success' => "You have been logged out successfully."
	],
	'avatar' => [
		'success' => 'Avatar updated successfully.'
	],
	'profile' => [
		'success' => "Profile was updated successfully."
	],
	'password' => [
		'success' => 'Your password was updated successfully.',
		'failed' => 'Your given current password does not match with the one in you account.'
	],
	'register' => [
		'success' => 'Welcome onboard! Your account was created successfully.',
		'taken' => 'It seems you are already registered with us. Please use login instead.'
	]
];