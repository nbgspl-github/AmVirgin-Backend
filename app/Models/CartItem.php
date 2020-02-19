<?php

namespace App\Models;

use App\Exceptions\MaxAllowedQuantityReachedException;
use App\Resources\Cart\CartItemResource;
use App\Traits\RetrieveResource;
use Illuminate\Database\Eloquent\Model;

class CartItem extends Model {
	use RetrieveResource;
	protected $product;
	protected $minAllowedQuantity;
	protected $maxAllowedQuantity;
	protected $cart;
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

	public function __construct() {
		parent::__construct();
		$this->minAllowedQuantity = 1;
		$this->maxAllowedQuantity = 10;
	}

	public static function make(\App\Classes\Cart\Cart $cart, Product $product = null): self {
		$cartItem = new CartItem();
		$cartItem->product = $product;
		$cartItem->minAllowedQuantity = 1;
		$cartItem->maxAllowedQuantity = 10;
		$cartItem->setQuantity(0);
		$cartItem->increaseQuantity();
		$cartItem->setCart($cart);
		$cartItem->setOptions([]);
		$cartItem->setUniqueId(sprintf('%s-%d', $cart->getSessionId(), $product->getKey()));
		return $cartItem;
	}

//	public function getOptionsAttribute() {
//		return jsonDecodeArray($this->options);
//	}
//
//	public function setOptionsAttribute($value) {
//		if ($value != null)
//			$this->options = jsonEncode($value);
//		else
//			$this->options = jsonEncode('[]');
//	}

	public function getUniqueId(): string {
		return $this->uniqueId;
	}

	public function getProduct(): Product {
		return $this->product;
	}

	public function getCart(): \App\Classes\Cart\Cart {
		return $this->cart;
	}

	public function setCart(\App\Classes\Cart\Cart $cart) {
		$this->cartId = $cart->getCartId();
		$this->cart = $cart;
	}

	public function getQuantity(): int {
		return $this->quantity;
	}

	public function getItemTotal(): float {
		return $this->itemTotal;
	}

	public function getOptions(): array {
		return $this->options;
	}

	public function getApplicablePrice() {
		return $this->originalPrice;
	}

	public function setOptions(array $options) {
		$this->options = $options;
	}

	public function increaseQuantity(int $incrementBy = 1) {
		if ($this->getQuantity() < $this->maxAllowedQuantity) {
			if ($incrementBy > 1 && ($this->getQuantity() + $incrementBy) > $this->maxAllowedQuantity) $incrementBy = 1;
			$this->setQuantity($this->getQuantity() + 1);
			$this->calculateItemTotal();
			$this->save();
		}
		else {
			throw new MaxAllowedQuantityReachedException($this);
		}
	}

	public function decreaseQuantity() {
		if ($this->getQuantity() > $this->minAllowedQuantity) {
			$this->setQuantity($this->getQuantity() - 1);
			$this->calculateItemTotal();
			$this->save();
		}
		else {
			$this->removeSelf();
		}
	}

	public function render() {
		$this->save();
		return new CartItemResource($this);
	}

	protected function calculateItemTotal() {
		$this->setItemTotal($this->getQuantity() * $this->getApplicablePrice());
	}

	protected function removeSelf() {
		$this->cart->destroyItem($this);
	}

	private function setUniqueId(string $uniqueId) {
		$this->uniqueId = $uniqueId;
	}

	private function setQuantity(int $quantity) {
		$this->quantity = $quantity;
	}

	private function setItemTotal(float $itemTotal) {
		$this->itemTotal = $itemTotal;
	}
}