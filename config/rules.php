<?php

/**
 * |----------------------------------------------------------------------------------------------------
 * |  This file contains all the validation rules used throughout the app.
 * |  Each rule group is divided into subsets of various categories, so use them through nesting.
 * |----------------------------------------------------------------------------------------------------
 */

use App\Constants\OfferTypes;
use App\Constants\ProductStatus;
use App\Interfaces\Tables;
use Illuminate\Validation\Rule;

define('RuleMaxInt', sprintf('max:%d', PHP_INT_MAX));
define('RuleMaxStock', sprintf('max:%d', 99999999));

return [

	'auth' => [
		/* Seller Auth Rules*/
		'seller' => [
			'exists' => [
				'type' => ['bail', 'required', Rule::in([1, 2, 3])],
				'email' => ['bail', 'email', 'required_if:type,1'],
				'mobile' => ['bail', 'digits:10', 'required_if:type,2,3'],
			],
			'login' => [
				'type' => ['bail', 'required', Rule::in([1, 2, 3])],
				'email' => ['bail', 'required_if:type,1', 'nullable'],
				'mobile' => ['bail', 'required_if:type,2,3', 'nullable', 'digits:10'],
				'password' => ['bail', 'required_if:type,1,2', 'string', 'min:4', 'max:64'],
				'otp' => ['bail', 'required_if:type,3', 'numeric', 'min:1111', 'max:9999'],
			],
			'register' => [
				'name' => ['bail', 'required', 'string', 'min:2', 'max:100'],
				'email' => ['bail', 'required', 'email'],
				'mobile' => ['bail', 'required', 'digits:10'],
				'password' => ['bail', 'required', 'string', 'min:4', 'max:64'],
				'otp' => ['bail', 'required', 'numeric', 'min:1111', 'max:9999'],
			],
		],

		/* Customer Auth Rules*/
		'customer' => [
			'exists' => [
				'type' => ['bail', 'required', Rule::in([1, 2, 3])],
				'email' => ['bail', 'email', 'required_if:type,1'],
				'mobile' => ['bail', 'digits:10', 'required_if:type,2,3'],
			],
			'login' => [
				'type' => ['bail', 'required', Rule::in([1, 2, 3])],
				'email' => ['bail', 'required_if:type,1', 'nullable'],
				'mobile' => ['bail', 'required_if:type,2,3', 'nullable', 'digits:10'],
				'password' => ['bail', 'required_if:type,1,2', 'string', 'min:4', 'max:64'],
				'otp' => ['bail', 'required_if:type,3', 'numeric', 'min:1111', 'max:9999'],
			],
			'register' => [
				'name' => ['bail', 'required', 'string', 'min:2', 'max:100'],
				'email' => ['bail', 'required', 'email', 'unique:customers,email'],
				'mobile' => ['bail', 'required', 'digits:10', 'unique:customers,mobile'],
				'password' => ['bail', 'required', 'string', 'min:4', 'max:64'],
				'otp' => ['bail', 'required', 'numeric', 'min:1111', 'max:9999'],
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

		'videos' => [
			'store' => [
				'title' => ['bail', 'required', 'string', 'min:1', 'max:256'],
				'description' => ['bail', 'required', 'string', 'min:1', 'max:2000'],
				'movieDBId' => ['bail', 'required', 'numeric', 'min:1', RuleMaxInt], // Later on, we'll add real API based validation here
				'imdbId' => ['bail', 'required', 'numeric', 'min:1', RuleMaxInt], // Later on, we'll add real API based validation here
				'releaseDate' => ['bail', 'required', 'date'],
				'averageRating' => ['bail', 'required', 'numeric', 'min:0.00', 'max:100.00'],
				'votes' => ['bail', 'required', 'numeric', 'min:0', RuleMaxInt],
				'popularity' => ['bail', 'required', 'numeric', 'min:0', RuleMaxInt],
				'genreId' => ['bail', 'required', 'exists:genres,id'],
				'serverId' => ['bail', 'required', 'exists:media-servers,id'],
				'mediaLanguageId' => ['bail', 'required', 'exists:media-languages,id'],
				'mediaQualityId' => ['bail', 'required', 'exists:media-qualities,id'],
				'poster' => ['bail', 'required', 'mimes:jpg,jpeg,png,bmp'],
				'backdrop' => ['bail', 'required', 'mimes:jpg,jpeg,png,bmp'],
				'previewUrl' => ['bail', 'required', 'string', 'min:6', 'max:2048'],
				'video' => ['bail', 'required', 'mimes:mkv,mp4,flv,avi,wmv'],
			],
			'update' => [

			],
		],

		'customers' => [
			'store' => [
				'name' => ['bail', 'required', 'string', 'min:4', 'max:50'],
				'mobile' => ['bail', 'required', 'digits:10', Rule::unique('customers', 'mobile')],
				'email' => ['bail', 'required', 'email', Rule::unique('customers', 'email')],
				'password' => ['bail', 'required', 'string', 'min:4', 'max:128'],
				'active' => ['bail', 'required', Rule::in([0, 1])],
			],
			'update' => [
				'name' => ['bail', 'required', 'string', 'min:4', 'max:50'],
				'mobile' => ['bail', 'required', 'digits:10'],
				'email' => ['bail', 'required', 'email'],
				'active' => ['bail', 'required', Rule::in([0, 1])],
			],
		],

		'categories' => [
			'store' => [
				'name' => ['bail', 'required', 'string', 'min:1', 'max:100'],
				'parentId' => ['bail', 'required', 'numeric'],
				'description' => ['bail', 'nullable', 'string', 'min:1', 'max:1000'],
				'visibility' => ['bail', 'required', Rule::in([0, 1])],
				'poster' => ['bail', 'nullable', 'image'],
			],
			'update' => [
				'name' => ['bail', 'required', 'string', 'min:1', 'max:100'],
				'parentId' => ['bail', 'required', 'numeric'],
				'description' => ['bail', 'nullable', 'string', 'min:1', 'max:1000'],
				'visibility' => ['bail', 'required', Rule::in([0, 1])],
				'poster' => ['bail', 'nullable', 'image'],
			],
		],

		'genres' => [
			'store' => [
				'name' => ['bail', 'required', 'string', 'min:1', 'max:100', Rule::unique(Tables::Genres, 'name')],
				'poster' => ['bail', 'nullable', 'image'],
				'status' => ['bail', 'required', Rule::in([0, 1])],
			],
			'update' => [
				'name' => ['bail', 'required', 'string', 'min:1', 'max:100'],
				'poster' => ['bail', 'nullable', 'mimes:jpg,jpeg,png,bmp'],
				'status' => ['bail', 'required', Rule::in([0, 1])],
			],
		],

		'servers' => [
			'store' => [

			],
			'update' => [

			],
		],
	],
];