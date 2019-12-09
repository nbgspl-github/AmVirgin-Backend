<?php

/**
 * |----------------------------------------------------------------------------------------------------
 * |  This file contains all the validation rules used throughout the app.
 * |  Each rule group is divided into subsets of various categories, so use them through nesting.
 * |----------------------------------------------------------------------------------------------------
 */

use App\Models\Customer;
use Illuminate\Validation\Rule;

return [

	'auth' => [
		/* Seller Auth Rules*/
		'seller' => [
			'check' => [
				'',
			],
			'login' => [
				'email' => ['bail', 'nullable', 'email', 'exists:sellers,email', 'required_without:mobile'],
				'mobile' => ['bail', 'nullable', 'digits:10', 'required_without:email'],
				'password' => ['bail', 'required', 'string', 'min:4', 'max:64'],
			],
			'logout' => [

			],
		],

		/* Customer Auth Rules*/
		'customer' => [
			'check' => [

			],
			'login' => [

			],
			'logout' => [

			],
		],

		/* Admin Auth Rules*/
		'admin' => [
			'check' => [

			],
			'login' => [

			],
			'logout' => [

			],
		],
	],

];