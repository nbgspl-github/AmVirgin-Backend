<?php

namespace App\Models;

use App\Resources\Cart\CartItemResource;
use App\Traits\RetrieveResource;
use Illuminate\Database\Eloquent\Model;

class CartItem extends Model {
	use RetrieveResource;
	const MinAllowedQuantity = 1;
	const MaxAllowedQuantity = 10;
	protected $table = 'cart-items';
	protected $fillable = [
		'cartId',
		'productId',
		'uniqueId',
		'quantity',
		'itemTotal',
		'options',
	];
	protected $hidden = [
		'created_at',
		'updated_at',
	];
	protected $casts = [
		'cartId' => 'integer',
		'productId' => 'integer',
		'uniqueId' => 'string',
		'quantity' => 'integer',
		'itemTotal' => 'float',
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

	public function getOptionsAttribute() {
		return jsonDecodeArray($this->options);
	}

	public function setOptionsAttribute($value) {
		if ($value != null)
			$this->options = jsonEncode($value);
		else
			$this->options = jsonEncode('[]');
	}

	public function render() {
		return new CartItemResource($this);
	}
}