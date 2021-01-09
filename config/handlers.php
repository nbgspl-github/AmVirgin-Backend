<?php
return [
	'orders' => [
		'seller' => [
			\App\Library\Enums\Orders\Status::ReadyForDispatch => \App\Http\Modules\Seller\Controllers\Api\Orders\Status\Handlers\ReadyForDispatch::class,
			\App\Library\Enums\Orders\Status::Dispatched => \App\Http\Modules\Seller\Controllers\Api\Orders\Status\Handlers\Dispatched::class,
			\App\Library\Enums\Orders\Status::Delivered => \App\Http\Modules\Seller\Controllers\Api\Orders\Status\Handlers\Delivered::class,
			\App\Library\Enums\Orders\Status::PendingDispatch => \App\Http\Modules\Seller\Controllers\Api\Orders\Status\Handlers\PendingDispatch::class,
			\App\Library\Enums\Orders\Status::OutForDelivery => \App\Http\Modules\Seller\Controllers\Api\Orders\Status\Handlers\OutForDelivery::class,
			\App\Library\Enums\Orders\Status::Cancelled => \App\Http\Modules\Seller\Controllers\Api\Orders\Status\Handlers\Cancelled::class,
		]
	]
];