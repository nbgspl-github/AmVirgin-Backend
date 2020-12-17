<?php
return [
	'orders' => [
		'seller' => [
			\App\Library\Enums\Orders\Status::ReadyForDispatch => \App\Http\Controllers\Api\Seller\Orders\Status\Handlers\ReadyForDispatch::class,
			\App\Library\Enums\Orders\Status::Dispatched => \App\Http\Controllers\Api\Seller\Orders\Status\Handlers\Dispatched::class,
			\App\Library\Enums\Orders\Status::Delivered => \App\Http\Controllers\Api\Seller\Orders\Status\Handlers\Delivered::class,
			\App\Library\Enums\Orders\Status::PendingDispatch => \App\Http\Controllers\Api\Seller\Orders\Status\Handlers\PendingDispatch::class,
			\App\Library\Enums\Orders\Status::OutForDelivery => \App\Http\Controllers\Api\Seller\Orders\Status\Handlers\OutForDelivery::class,
			\App\Library\Enums\Orders\Status::Cancelled => \App\Http\Controllers\Api\Seller\Orders\Status\Handlers\OutForDelivery::class,
		]
	]
];