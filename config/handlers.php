<?php
return [
	'orders' => [
		'seller' => [
			\App\Enums\Orders\Status::ReadyForDispatch => \App\Http\Controllers\Api\Seller\Orders\Status\Handlers\ReadyForDispatch::class,
			\App\Enums\Orders\Status::Dispatched => \App\Http\Controllers\Api\Seller\Orders\Status\Handlers\Dispatched::class,
			\App\Enums\Orders\Status::Delivered => \App\Http\Controllers\Api\Seller\Orders\Status\Handlers\Dispatched::class,
			\App\Enums\Orders\Status::PendingDispatch => \App\Http\Controllers\Api\Seller\Orders\Status\Handlers\Dispatched::class,
			\App\Enums\Orders\Status::OutForDelivery => \App\Http\Controllers\Api\Seller\Orders\Status\Handlers\Dispatched::class,
		]
	]
];