<?php

namespace App\Models;

use App\Resources\Cart\CartItemResource;
use App\Traits\RetrieveResource;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model {
	use RetrieveResource;
	protected $table = 'carts';
	protected $fillable = [
		'sessionId',
		'addressId',
		'customerId',
		'itemCount',
		'subTotal',
		'tax',
		'paymentMode',
		'status',
	];

	public static function retrieve(string $sessionId, int $customerId = 0) {
		if ($customerId == 0)
			return self::where('sessionId', $sessionId)->first();
		else
			return self::where('sessionId', $sessionId)->where('customerId', $customerId)->first();
	}

	public static function retrieveThrows(string $sessionId, int $customerId = 0) {
		if ($customerId == 0)
			return self::where('sessionId', $sessionId)->firstOrFail();
		else
			return self::where('sessionId', $sessionId)->where('customerId', $customerId)->firstOrFail();
	}

	public function items() {
		return $this->hasMany(CartItem::class, 'cartId');
	}

	public function session() {
		return $this->hasOne(CartSession::class, 'sessionId', 'sessionId');
	}

	public function render() {
		return [
			'session' => $this->session()->first(),
			'items' => CartItemResource::collection($this->items()->get()),
		];
	}
}