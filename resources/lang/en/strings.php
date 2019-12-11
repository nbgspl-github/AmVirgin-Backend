<?php

return [
	'auth' => [
		'check' => [
			'failed' => 'User not found for those keys.',
			'success' => 'User exists for those keys.',
		],
		'login' => [
			'success' => 'You have been logged in successfully.',
			'failed' => 'Your provided credentials are invalid.',
			'404' => 'We could not find a user for that key.',
			'check' => 'Resource exists.',
			'check-failed' => 'Resource not found.',
		],
		'register' => [
			'success' => 'You have been registered successfully.',
			'failed' => 'Failed to sign you up.',
			'404' => 'We could not find a user for that key.',
			'check' => 'Resource exists.',
			'check-failed' => 'Resource not found.',
		],
		'logout' => [
			'success' => 'Successfully logged out.',
			'failed' => 'Failed to log you out.',
		],
	],
];