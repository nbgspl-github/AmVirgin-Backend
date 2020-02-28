<?php

namespace App\Classes\Cart;

use App\Constants\OfferTypes;
use App\Exceptions\MaxAllowedQuantityReachedException;
use App\Models\Product;
use App\Resources\Products\Customer\ProductResource;
use JsonSerializable;
use stdClass;

class CartItem extends stdClass implements JsonSerializable {
	protected int $maxAllowedQuantity;

	protected int $minAllowedQuantity;

	protected int $key;

	protected string $uniqueId;

	protected int $quantity;

	protected float $itemTotal;

	protected Product $product;

	protected \App\Models\Cart $cart;

	protected array $attributes;

	public function __construct(\App\Models\Cart $cart, int $key, $attributes = []) {
		$this->setMinAllowedQuantity(1);
		$this->setMaxAllowedQuantity(10);
		$this->setKey($key);
		$this->setProduct(Product::retrieve($this->getKey()));
		$this->setCart($cart);
		$this->setQuantity(0);
		$this->setUniqueId(sprintf('%s-%d', $cart->sessionId, $key));
		$this->setAttributes($attributes);
	}

	public function getMaxAllowedQuantity(): int {
		return $this->maxAllowedQuantity;
	}

	protected function setMaxAllowedQuantity(int $maxAllowedQuantity): CartItem {
		$this->maxAllowedQuantity = $maxAllowedQuantity;
		return $this;
	}

	public function getMinAllowedQuantity(): int {
		return $this->minAllowedQuantity;
	}

	protected function setMinAllowedQuantity(int $minAllowedQuantity): CartItem {
		$this->minAllowedQuantity = $minAllowedQuantity;
		return $this;
	}

	public function getItemTotal(): int {
		return $this->itemTotal;
	}

	protected function setItemTotal(float $itemTotal): CartItem {
		$this->itemTotal = $itemTotal;
		return $this;
	}

	public function getKey(): int {
		return $this->key;
	}

	public function setKey(int $key): CartItem {
		$this->key = $key;
		return $this;
	}

	public function getUniqueId(): string {
		return $this->uniqueId;
	}

	public function setUniqueId(string $uniqueId): CartItem {
		$this->uniqueId = $uniqueId;
		return $this;
	}

	public function getQuantity(): int {
		return $this->quantity;
	}

	public function setQuantity(int $quantity): CartItem {
		$this->quantity = $quantity;
		$this->itemTotal = $this->getApplicablePrice() * $this->getQuantity();
		return $this;
	}

	public function getProduct(): Product {
		return $this->product;
	}

	public function setProduct(Product $product): CartItem {
		$this->product = $product;
		return $this;
	}

	public function getCart(): \App\Models\Cart {
		return $this->cart;
	}

	public function setCart(\App\Models\Cart $cart): CartItem {
		$this->cart = $cart;
		return $this;
	}

	public function getAttributes(): array {
		return $this->attributes;
	}

	public function setAttributes(array $attributes): CartItem {
		$this->attributes = $attributes;
		return $this;
	}

	public function increaseQuantity(int $incrementBy = 1) {
		if ($this->getQuantity() < $this->maxAllowedQuantity) {
			if ($incrementBy > 1 && ($this->getQuantity() + $incrementBy) > $this->maxAllowedQuantity) $incrementBy = 1;
			$this->setQuantity($this->getQuantity() + 1);
		}
		else {
			throw new MaxAllowedQuantityReachedException($this);
		}
	}

	public function decreaseQuantity() {
		if ($this->getQuantity() > $this->minAllowedQuantity)
			$this->setQuantity($this->getQuantity() - 1);
		else {
			$this->removeSelf();
		}
	}

	public function jsonSerialize() {
		return [
			'key' => $this->getKey(),
			'product' => new ProductResource($this->getProduct()),
			'quantity' => $this->getQuantity(),
			'uniqueId' => $this->getUniqueId(),
			'itemTotal' => $this->getItemTotal(),
			'attributes' => $this->getAttributes(),
		];
	}

	protected function getApplicablePrice(): float {
		$offerType = $this->getProduct()->getOfferType();
		$offerValue = $this->getProduct()->getOfferValue();
		$originalPrice = $this->getProduct()->getOriginalPrice();
		if ($offerValue > 0) {
			if ($offerType == OfferTypes::FlatRate) {
				if ($originalPrice > $offerValue) {
					return $originalPrice - $offerValue;
				}
				else {
					return 0;
				}
			}
			else if ($offerType == OfferTypes::Percentage) {
				$amount = ($offerValue / 100.0) * $originalPrice;
				if ($originalPrice > $amount) {
					return $originalPrice - $amount;
				}
				else {
					return 0;
				}
			}
			else {
				return $this->product->getOriginalPrice();
			}
		}
		return $this->product->getOriginalPrice();
	}

	protected function removeSelf() {
		$this->cart->destroyItem($this);
	}
}