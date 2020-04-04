<?php
return [
	'price' => [
		'key' => 'price',
		'static' => [
			'divisions' => 5,
		],
		'boundaries' => [
			500 => 1,
			1000 => 1,
			5000 => 1,
			10000 => 2,
			20000 => 3,
			40000 => 4,
			60000 => 5,
		],
	],
];
