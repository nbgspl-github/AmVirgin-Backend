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
			'exists' => [
				'email' => ['bail', 'required_without:mobile', 'email'],
				'mobile' => ['bail', 'required_without:email', 'digits:10'],
			],
			'login' => [
				'email' => ['bail', 'nullable', 'email', 'exists:sellers,email', 'required_without:mobile'],
				'mobile' => ['bail', 'nullable', 'digits:10', 'exists:sellers,mobile', 'required_without:email'],
				'password' => ['bail', 'required', 'string', 'min:4', 'max:64'],
			],
			'logout' => [

			],
			'register' => [
				'email' => ['bail', 'nullable', 'email', 'unique:sellers,email'],
				'name' => ['bail', 'required', 'string', 'min:2', 'max:100'],
				'mobile' => ['bail', 'nullable', 'digits:10', 'unique:sellers,mobile'],
				'password' => ['bail', 'required', 'string', 'min:4', 'max:64'],
			],
		],

		/* Customer Auth Rules*/
		'customer' => [
			'exists' => [
				'email' => ['bail', 'required_without:mobile', 'email'],
				'mobile' => ['bail', 'required_without:email', 'digits:10'],
			],
			'login' => [
				'email' => ['bail', 'nullable', 'email', 'exists:customers,email', 'required_without:mobile'],
				'mobile' => ['bail', 'nullable', 'digits:10', 'exists:customers,mobile', 'required_without:email'],
				'password' => ['bail', 'required', 'string', 'min:4', 'max:64'],
			],
			'logout' => [

			],
			'register' => [
				'email' => ['bail', 'nullable', 'email', 'unique:customers,email'],
				'name' => ['bail', 'required', 'string', 'min:2', 'max:100'],
				'mobile' => ['bail', 'nullable', 'digits:10', 'unique:customers,mobile'],
				'password' => ['bail', 'required', 'string', 'min:4', 'max:64'],
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