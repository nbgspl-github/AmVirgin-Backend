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
			'taken' => 'Either of your email or mobile number is already taken.',
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
		'denied' => 'You account is temporarily suspended. Please try again in a while.',
	],
	'category' => [
		'not-found' => 'Category not found for that key.',
		'store' => [
			'success' => 'Attribute created successfully.',
			'failed' => 'Failed to create attribute.',
		],
	],
	'customer' => [
		'store' => [
			'success' => 'Customer details saved successfully.',
		],
		'update' => [
			'success' => 'Customer details updated successfully.',
		],
		'not-found' => 'Could not find customer for that key.',
	],

	'product' => [
		'store' => [
			'success' => 'Product created successfully.',
			'failed' => 'Failed to create product.',
		],
	],

	'attribute' => [
		'not-found' => 'Attribute not found for that key',
	],

	'slider' => [
		'empty-data' => 'Could not find any sliders at this moment.',
	],

	'genre' => [
		'not-found' => 'Could not find any genre with that key.',
		'store' => [
			'success' => 'Genre created successfully.',
		],
	],

	'server' => [
		'not-found' => 'Could not find any server with that key.',
		'store' => [
			'success' => 'Media server created successfully.',
		],
	],
];