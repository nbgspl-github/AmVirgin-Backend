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
			'update' => [
				'profile' => [

				],
				'avatar' => [
					'avatar' => ['bail', 'required', 'image', 'min:1', 'max:4096'],
				],
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
				'productName' => ['bail', 'required', 'string', 'min:1', 'max:500'],
				'categoryId' => ['bail', 'required', 'exists:categories,id'],
				'productType' => ['bail', 'required', 'string', 'min:1', 'max:256'],
				'productMode' => ['bail', 'required', 'string', 'min:1', 'max:256'],
				'listingType' => ['bail', 'required', 'string', 'min:1', 'max:256'],
				'originalPrice' => ['bail', 'required', 'numeric', 'min:1', 'max:10000000'],
				'offerType' => ['bail', 'required', Rule::in([OfferTypes::FlatRate, OfferTypes::Percentage])],
				'offerValue' => ['bail', 'required', 'numeric', 'min:1', 'max:10000000'],
				'currency' => ['bail', 'nullable', 'string', 'min:2', 'max:5', 'exists:currencies,code'],
				'taxRate' => ['bail', 'required', 'numeric', 'min:0.00', 'max:99.99'],
				'countryId' => ['bail', 'required', 'exists:countries,id'],
				'stateId' => ['bail', 'required', 'numeric', 'exists:states,id'],
				'cityId' => ['bail', 'required', 'numeric', 'exists:cities,id'],
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
				'longDescription' => ['bail', 'required', 'string', 'min:1', 'max:5000'],
				'sku' => ['bail', 'required', 'string', 'min:1', 'max:256'],
				'files.*' => ['bail', 'required', 'mimes:jpg,jpeg,png,bmp', 'min:1', 'max:5120'],
				'attributes' => ['bail', 'required'],
			],
			'update' => [
				'productName' => ['bail', 'required', 'string', 'min:1', 'max:500'],
				'categoryId' => ['bail', 'required', 'exists:categories,id'],
				'productType' => ['bail', 'required', 'string', 'min:1', 'max:256'],
				'productMode' => ['bail', 'required', 'string', 'min:1', 'max:256'],
				'listingType' => ['bail', 'required', 'string', 'min:1', 'max:256'],
				'originalPrice' => ['bail', 'required', 'numeric', 'min:1', 'max:10000000'],
				'offerType' => ['bail', 'required', Rule::in([OfferTypes::FlatRate, OfferTypes::Percentage])],
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
				'longDescription' => ['bail', 'required', 'string', 'min:1', 'max:5000'],
				'sku' => ['bail', 'required', 'string', 'min:1', 'max:256'],
				'files.*' => ['bail', 'required', 'mimes:jpg,jpeg,png,bmp', 'min:1', 'max:5120'],
				'attributes.*.key' => ['bail', 'required', 'exists:attributes:id'],
				'attributes.*.values.*' => ['bail', 'required', 'exists:attribute-values:id'],
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
		'home-banner' => [
			'store' => [
				'title' => ['bail', 'required', 'string', 'min:1', 'max:256'],

				'banner' => ['bail', 'required', 'mimes:jpg,jpeg,png,bmp'],
				'target' => ['bail', 'required', 'url'],
				'stars' => ['bail', 'nullable', 'numeric', 'min:0', 'max:5'],
				'active' => ['bail', 'required', 'boolean'],
			],
			'update' => [
				'title' => ['bail', 'required', 'string', 'min:1', 'max:256'],
				'banner' => ['bail', 'nullable', 'mimes:jpg,jpeg,png,bmp'],
				'target' => ['bail', 'required', 'url'],
				'stars' => ['bail', 'nullable', 'numeric', 'min:0', 'max:5'],
				'active' => ['bail', 'required', 'boolean'],
			],
		],

		'videos' => [
			'attributes' => [
				'update' => [
					'title' => ['bail', 'required', 'string', 'min:1', 'max:500'],
					'description' => ['bail', 'required', 'string', 'min:1', 'max:2000'],
					'duration' => ['bail', 'required', 'regex:/^(?:(?:([01]?\d|2[0-3]):)?([0-5]?\d):)?([0-5]?\d)$/'],
					'released' => ['bail', 'required', 'date'],
					'cast' => ['bail', 'required', 'string', 'min:1', 'max:500'],
					'director' => ['bail', 'required', 'string', 'min:1', 'max:256'],
					'genreId' => ['bail', 'required', 'exists:genres,id'],
					'rating' => ['bail', 'required', 'numeric', 'min:0.00', 'max:5.00'],
					'pgRating' => ['bail', 'required', Rule::in(['G', 'PG', 'PG-13', 'R', 'NC-17'])],
					'subscriptionType' => ['bail', 'required', Rule::in(['free', 'paid', 'subscription'])],
					'price' => ['bail', 'nullable', 'required_unless:subscriptionType,free,subscription', 'numeric', 'min:0', 'max:10000'],
					'rank' => ['bail', 'nullable', 'gte:1', 'lt:11'],
				],
			],
			'store' => [
				'title' => ['bail', 'required', 'string', 'min:1', 'max:500'],
				'description' => ['bail', 'required', 'string', 'min:1', 'max:2000'],
				'duration' => ['bail', 'required', 'regex:/^(?:(?:([01]?\d|2[0-3]):)?([0-5]?\d):)?([0-5]?\d)$/'],
				'released' => ['bail', 'required', 'date'],
				'cast' => ['bail', 'required', 'string', 'min:1', 'max:500'],
				'director' => ['bail', 'required', 'string', 'min:1', 'max:256'],
				'genreId' => ['bail', 'required', 'exists:genres,id'],
				'rating' => ['bail', 'required', 'numeric', 'min:0.00', 'max:5.00'],
				'pgRating' => ['bail', 'required', Rule::in(['G', 'PG', 'PG-13', 'R', 'NC-17'])],
				'subscriptionType' => ['bail', 'required', Rule::in(['free', 'paid', 'subscription'])],
				'price' => ['bail', 'nullable', 'required_unless:subscriptionType,free,subscription', 'numeric', 'min:0.01', 'max:10000.00'],
				'rank' => ['bail', 'nullable', 'gte:1', 'lt:11'],
			],
			'update' => [

			],
			'snaps' => [
				'store' => [
					'image.*' => ['bail', 'required', 'image', 'min:1', 'max:5120'],
				],
				'update' => [

				],
			],
			'media' => [
				'store' => [

				],
				'update' => [
					'poster' => ['bail', 'nullable', 'image', 'min:1', 'max:5096'],
					'backdrop' => ['bail', 'nullable', 'image', 'min:1', 'max:5096'],
					'trailer' => ['bail', 'nullable', 'mimetypes:video/avi,video/mpeg,video/mp4', 'min:1', 'max:131072'],
				],
			],
			'content' => [
				'store' => [

				],
				'update' => [
					'title.*' => ['bail', 'required', 'string', 'min:1', 'max:256'],
					'description.*' => ['bail', 'required', 'string', 'min:1', 'max:5000'],
					'duration.*' => ['bail', 'required', 'date_format:H:i:s'],
					'language.*' => ['bail', 'required', 'exists:media-languages,id'],
					'quality.*' => ['bail', 'required', 'exists:media-qualities,id'],
					'video.*' => ['bail', 'required', 'mimetypes:video/avi,video/mpeg,video/quicktime,video/mp4,video/mkv'],
					'subtitle.*' => ['bail', 'nullable', 'min:1', 'max:5120'],
					'source.*' => ['bail', 'nullable'],
				],
			],
		],

		'tv-series' => [
			'store' => [
				'title' => ['bail', 'required', 'string', 'min:1', 'max:500'],
				'description' => ['bail', 'required', 'string', 'min:1', 'max:2000'],
				'duration' => ['bail', 'required', 'regex:/^(?:(?:([01]?\d|2[0-3]):)?([0-5]?\d):)?([0-5]?\d)$/'],
				'released' => ['bail', 'required', 'date'],
				'cast' => ['bail', 'required', 'string', 'min:1', 'max:500'],
				'director' => ['bail', 'required', 'string', 'min:1', 'max:256'],
				'genreId' => ['bail', 'required', 'exists:genres,id'],
				'rating' => ['bail', 'required', 'numeric', 'min:0.00', 'max:5.00'],
				'pgRating' => ['bail', 'required', Rule::in(['G', 'PG', 'PG-13', 'R', 'NC-17'])],
				'subscriptionType' => ['bail', 'required', Rule::in(['free', 'paid', 'subscription'])],
				'price' => ['bail', 'nullable', 'required_unless:subscriptionType,free,subscription', 'numeric', 'min:0', 'max:10000'],
				'rank' => ['bail', 'nullable', 'gte:1', 'lt:11'],
			],
			'update' => [
				'attributes' => [
					'title' => ['bail', 'required', 'string', 'min:1', 'max:500'],
					'description' => ['bail', 'required', 'string', 'min:1', 'max:2000'],
					'duration' => ['bail', 'required', 'regex:/^(?:(?:([01]?\d|2[0-3]):)?([0-5]?\d):)?([0-5]?\d)$/'],
					'released' => ['bail', 'required', 'date'],
					'cast' => ['bail', 'required', 'string', 'min:1', 'max:500'],
					'director' => ['bail', 'required', 'string', 'min:1', 'max:256'],
					'trailer' => ['bail', 'nullable', 'mimetypes:video/avi,video/mpeg,video/quicktime,video/mp4'],
					'poster' => ['bail', 'nullable', 'mimes:jpg,jpeg,png,bmp', 'min:1', 'max:5120'],
					'backdrop' => ['bail', 'nullable', 'mimes:jpg,jpeg,png,bmp', 'min:1', 'max:5120'],
					'genreId' => ['bail', 'required', 'exists:genres,id'],
					'rating' => ['bail', 'required', 'numeric', 'min:0.00', 'max:5.00'],
					'pgRating' => ['bail', 'required', Rule::in(['G', 'PG', 'PG-13', 'R', 'NC-17'])],
					'subscriptionType' => ['bail', 'required', Rule::in(['free', 'paid', 'subscription'])],
					'price' => ['bail', 'nullable', 'required_unless:subscriptionType,free,subscription', 'numeric', 'min:0', 'max:10000'],
					'rank' => ['bail', 'nullable', 'gte:1', 'lt:11'],
				],
				'content' => [
					'title.*' => ['bail', 'required', 'string', 'min:1', 'max:256'],
					'description.*' => ['bail', 'required', 'string', 'min:1', 'max:5000'],
					'duration.*' => ['bail', 'required', 'date_format:H:i:s'],
					'season.*' => ['bail', 'required', 'numeric', 'min:1', 'max:25'],
					'language.*' => ['bail', 'required', 'exists:media-languages,id'],
					'quality.*' => ['bail', 'required', 'exists:media-qualities,id'],
					'episode.*' => ['bail', 'required', 'numeric', 'min:1', 'max:250', 'distinct'],
					'video.*' => ['bail', 'required', 'mimetypes:video/avi,video/mpeg,video/quicktime,video/mp4,video/mkv'],
				],
			],
			'attributes' => [
				'update' => [
					'title' => ['bail', 'required', 'string', 'min:1', 'max:500'],
					'description' => ['bail', 'required', 'string', 'min:1', 'max:2000'],
					'duration' => ['bail', 'required', 'regex:/^(?:(?:([01]?\d|2[0-3]):)?([0-5]?\d):)?([0-5]?\d)$/'],
					'released' => ['bail', 'required', 'date'],
					'cast' => ['bail', 'required', 'string', 'min:1', 'max:500'],
					'director' => ['bail', 'required', 'string', 'min:1', 'max:256'],
					'genreId' => ['bail', 'required', 'exists:genres,id'],
					'rating' => ['bail', 'required', 'numeric', 'min:0.00', 'max:5.00'],
					'pgRating' => ['bail', 'required', Rule::in(['G', 'PG', 'PG-13', 'R', 'NC-17'])],
					'subscriptionType' => ['bail', 'required', Rule::in(['free', 'paid', 'subscription'])],
					'price' => ['bail', 'nullable', 'required_unless:subscriptionType,free,subscription', 'numeric', 'min:0', 'max:10000'],
					'rank' => ['bail', 'nullable', 'gte:1', 'lt:11'],
				],
			],
			'media' => [
				'store' => [

				],
				'update' => [
					'poster' => ['bail', 'nullable', 'image', 'min:1', 'max:5096'],
					'backdrop' => ['bail', 'nullable', 'image', 'min:1', 'max:5096'],
					'trailer' => ['bail', 'nullable', 'mimetypes:video/avi,video/mpeg,video/mp4', 'min:1', 'max:131072'],
				],
			],
			'content' => [
				'store' => [

				],
				'update' => [
					'title.*' => ['bail', 'required', 'string', 'min:1', 'max:256'],
					'description.*' => ['bail', 'required', 'string', 'min:1', 'max:5000'],
					'duration.*' => ['bail', 'required', 'date_format:H:i:s'],
					'season.*' => ['bail', 'required', 'numeric', 'min:1', 'max:25'],
					'language.*' => ['bail', 'required', 'exists:media-languages,id'],
					'quality.*' => ['bail', 'required', 'exists:media-qualities,id'],
					'episode.*' => ['bail', 'required', 'numeric', 'min:1', 'max:250'],
					'video.*' => ['bail', 'required', 'mimetypes:video/avi,video/mpeg,video/quicktime,video/mp4,video/mkv'],
					'subtitle.*' => ['bail', 'nullable', 'min:1', 'max:5120'],
					'source.*' => ['bail', 'nullable'],
				],
			],
			'snaps' => [
				'store' => [
					'image.*' => ['bail', 'required', 'image', 'min:1', 'max:5120'],
				],
				'update' => [

				],
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

		'sellers' => [
			'store' => [
				'name' => ['bail', 'required', 'string', 'min:4', 'max:50'],
				'mobile' => ['bail', 'required', 'digits:10', Rule::unique('sellers', 'mobile')],
				'email' => ['bail', 'required', 'email', Rule::unique('sellers', 'email')],
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

		'categories-banner' => [
			'store' => [
				'title' => ['bail', 'required', 'string', 'min:1', 'max:500'],
				'order' => ['bail', 'required', 'numeric'],
				'status' => ['bail', 'required', ' boolean'],
				'sectionTitle' => ['bail', 'nullable', 'string', 'min:1', 'max:500'],
				'layoutType' => ['bail', 'required', 'string', 'min:1', 'max:100'],
				'validFrom' => ['bail', 'required', 'string'],
				'validUntil' => ['bail', 'required', 'string'],
				'image.*' => ['bail', 'nullable', 'image'],
				//'hasValidity' => ['bail', 'required', ' boolean'],
			],
			'update' => [
				'title' => ['bail', 'required', 'string', 'min:1', 'max:500'],
				'order' => ['bail', 'required', 'numeric'],
				'status' => ['bail', 'required', ' boolean'],
				'sectionTitle' => ['bail', 'nullable', 'string', 'min:1', 'max:500'],
				'layoutType' => ['bail', 'required', 'string', 'min:1', 'max:100'],
				'validFrom' => ['bail', 'required', 'string'],
				'validUntil' => ['bail', 'required', 'string'],
				//'image' => ['bail', 'nullable', 'image'],
				//'hasValidity' => ['bail', 'required', Rule::in([0, 1])],
			],
		],

		'categories' => [
			'store' => [
				'name' => ['bail', 'required', 'string', 'min:1', 'max:100'],
				'parentId' => ['bail', 'required', 'numeric'],
				'description' => ['bail', 'nullable', 'string', 'min:1', 'max:1000'],
				'visibility' => ['bail', 'required', Rule::in([0, 1])],
				'poster' => ['bail', 'nullable', 'image'],
				'icon' => ['bail', 'nullable', 'image'],
			],
			'update' => [
				'name' => ['bail', 'required', 'string', 'min:1', 'max:100'],
				'parentId' => ['bail', 'required', 'numeric'],
				'description' => ['bail', 'nullable', 'string', 'min:1', 'max:1000'],
				'visibility' => ['bail', 'required', Rule::in([0, 1])],
				'poster' => ['bail', 'nullable', 'image'],
				'icon' => ['bail', 'nullable', 'image'],
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

		'subscription-plans' => [
			'store' => [
				'name' => ['bail', 'required', 'string', 'min:1', 'max:100', Rule::unique(Tables::SubscriptionPlans, 'name')],
				'description' => ['bail', 'required', 'string', 'min:1', 'max:5000'],
				'originalPrice' => ['bail', 'required', 'numeric', 'min:0.00', 'max:100000.00'],
				'offerPrice' => ['bail', 'required', 'numeric', 'min:0.00', 'max:100000.00'],
				'banner' => ['bail', 'required', 'image'],
				'duration' => ['bail', 'required', 'numeric', 'min:0', 'max:1200'],
				'active' => ['bail', 'required', 'boolean'],
			],

			'update' => [
				'name' => ['bail', 'required', 'string', 'min:1', 'max:100'],
				'description' => ['bail', 'required', 'string', 'min:1', 'max:5000'],
				'originalPrice' => ['bail', 'required', 'numeric', 'min:0.00', 'max:100000.00'],
				'offerPrice' => ['bail', 'required', 'numeric', 'min:0.00', 'max:100000.00'],
				'banner' => ['bail', 'nullable', 'image'],
				'duration' => ['bail', 'required', 'numeric', 'min:0', 'max:1200'],
				'active' => ['bail', 'required', 'boolean'],
			],
		],
	],
];
