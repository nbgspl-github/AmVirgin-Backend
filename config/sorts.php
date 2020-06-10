<?php
return [
	'shop' => [
		'relevance' => [
			'label' => 'Relevance',
			'class' => \App\Classes\Sorting\Relevance::class,
		],
		'price-ascending' => [
			'label' => 'Price: Low to High',
			'class' => \App\Classes\Sorting\PriceAscending::class,
		],
		'price-descending' => [
			'label' => 'Price: High to Low',
			'class' => \App\Classes\Sorting\PriceDescending::class,
		],
		'popularity' => [
			'label' => 'Popularity',
			'class' => \App\Classes\Sorting\Popularity::class,
		],
		'what-is-new' => [
			'label' => 'What\'s new!',
			'class' => \App\Classes\Sorting\WhatIsNew::class,
		],
		'discount-descending' => [
			'label' => 'Better Discount',
			'class' => \App\Classes\Sorting\DiscountDescending::class,
		],
		'discount-ascending' => [
			'label' => 'Least Discount',
			'class' => \App\Classes\Sorting\DiscountAscending::class,
		],
	],
];