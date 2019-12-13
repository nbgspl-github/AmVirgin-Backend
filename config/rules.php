<?php

/**
 * |----------------------------------------------------------------------------------------------------
 * |  This file contains all the validation rules used throughout the app.
 * |  Each rule group is divided into subsets of various categories, so use them through nesting.
 * |----------------------------------------------------------------------------------------------------
 */

use App\Constants\OfferTypes;
use App\Constants\ProductStatus;
use Illuminate\Validation\Rule;

define('RuleMaxInt', sprintf('max:%d', PHP_INT_MAX));
define('RuleMaxStock', sprintf('max:%d', 99999999));

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

	'seller' => [
		'category' => [
			'store' => [
				'attributeName' => ['bail', 'required', 'string', 'min:1', 'max:50', 'unique:attributes,name'],
			],
		],
		'product' => [
			'store' => [
				'productName' => ['bail', 'required', 'string', 'min:1', 'max:500', 'unique:products,name'],
				'categoryId' => ['bail', 'required', 'exists:categories,id'],
				'sellerId' => ['bail', 'required', 'exists:sellers,id'],
				'productType' => ['bail', 'required', 'string', 'min:1', 'max:256'],
				'productMode' => ['bail', 'required', 'string', 'min:1', 'max:256'],
				'listingType' => ['bail', 'required', 'string', 'min:1', 'max:256'],
				'originalPrice' => ['bail', 'required', 'numeric', 'min:1', 'max:10000000'],
				'offerType' => ['bail', 'required', Rule::in([OfferTypes::FlatRate, OfferTypes::Percentage])], /*Since we only have two offer types for now, it's 0 and 1, later on we'll add as required.*/
				'offerValue' => ['bail', 'required', 'numeric', 'min:1', 'max:10000000'],
				'currency' => ['bail', 'nullable', 'string', 'min:2', 'max:5', 'exists:currencies,code'],
				'taxRate' => ['bail', 'required', 'numeric', 'min:0.00', 'max:99.99'],
				'countryId' => ['bail', 'required', 'exists:countries,id'],
				'stateId' => ['bail', 'required', 'numeric', 'min:1', 'max:9999999'],
				'cityId' => ['bail', 'required', 'numeric', 'min:1', RuleMaxInt],
				'zipCode' => ['bail', 'required', 'min:1', RuleMaxInt],
				'address' => ['bail', 'required', 'string', 'min:2', 'max:500'],
				'status' => ['bail', 'nullable', Rule::in([ProductStatus::DifferentStatus, ProductStatus::SomeOtherStatus, ProductStatus::SomeStatus])],
				'promoted' => ['bail', 'boolean'],
				'promotionStart' => ['bail', 'required_with:promoted', 'date'],
				'promotionEnd' => ['bail', 'required_with:promoted', 'date', 'after:promotionStart'],
				'visibility' => ['bail', 'boolean'],
				'stock' => ['bail', 'required', 'numeric', 'min:0', RuleMaxStock],
				'shippingCostType' => ['bail', 'required', Rule::in(['free', 'chargeable'])],
				'shippingCost' => ['bail', 'required_if:shippingCostType,chargeable'],
				'draft' => ['bail', 'boolean'],
				'shortDescription' => ['bail', 'required', 'string', 'min:1', 'max:1000'],
				'longDescription' => ['bail', 'required', 'string', 'min:1', 'max:10000'],
				'sku' => ['bail', 'required', 'string', 'min:1', 'max:256'],
			],
		],
	],

	'admin' => [
		'sliders' => [
			'store' => [
				'title' => ['bail', 'required', 'string', 'min:1', 'max:256'],
				'description' => ['bail', 'required', 'string', 'min:1', 'max:2048'],
				'poster' => ['bail', 'required', 'mimes:jpg,jpeg,png,bmp'],
				'target' => ['bail', 'required', 'url'],
				'stars' => ['bail', 'nullable', 'numeric', 'min:0', 'max:5'],
				'active' => ['bail', 'required', 'boolean'],
			],
			'update' => [
				'title' => ['bail', 'required', 'string', 'min:1', 'max:256'],
				'description' => ['bail', 'required', 'string', 'min:1', 'max:2048'],
				'poster' => ['bail', 'nullable', 'mimes:jpg,jpeg,png,bmp'],
				'target' => ['bail', 'required', 'url'],
				'stars' => ['bail', 'nullable', 'numeric', 'min:0', 'max:5'],
				'active' => ['bail', 'required', 'boolean'],
			],
		],
	],
];