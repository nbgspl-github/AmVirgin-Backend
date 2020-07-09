<?php

namespace App\Models;

use App\Classes\Eloquent\ModelExtended;
use App\Queries\SellerOrderQuery;
use App\Traits\DynamicAttributeNamedMethods;
use App\Traits\RetrieveCollection;
use App\Traits\RetrieveResource;

class SellerOrder extends ModelExtended {
	use DynamicAttributeNamedMethods, RetrieveResource, RetrieveCollection;

	protected $table = 'seller-orders';
	protected $fillable = [
		'sellerId',
		'customerId',
		'orderId',
		'orderNumber',
		'status',
	];
	public const AllowedStatuses = [
		ShipmentPending => [
			ShipmentPlaced,
		],
		ShipmentPlaced => [
			ShipmentReadyForDispatch,
			ShipmentDispatched,
			ShipmentCancelled,
		],
		ShipmentReadyForDispatch => [
			ShipmentDispatched,
		],
		ShipmentDispatched => [
			ShipmentOutForDelivery,
			ShipmentRescheduled,
		],
		ShipmentRescheduled => [
			ShipmentOutForDelivery,
		],
		ShipmentOutForDelivery => [
			ShipmentRescheduled,
			ShipmentDelivered,
		],
		ShipmentCancelled => [
			ShipmentRefunded,
			ShipmentRefundProcessing,
		],
		ShipmentRefundProcessing => [
			ShipmentRefunded,
		],
	];

	public function seller () {
		return $this->belongsTo('App\Models\Auth\Seller', 'sellerId');
	}

	public function customer () {
		return $this->belongsTo(Auth\Customer::class, 'customerId');
	}

	public function items () {
		return $this->hasMany('App\Models\SellerOrderItem', 'sellerOrderId');
	}

	public function item () {
		return $this->hasMany('App\Models\SellerOrderItem', 'sellerOrderId')->with('productDetails');
	}

	public function order () {
		return $this->belongsTo(Order::class, 'orderId')->with('address');
	}

	public static function startQuery () : SellerOrderQuery {
		return SellerOrderQuery::begin();
	}
}
