<?php

namespace App\Classes\Cart;

use App\Constants\OfferTypes;
use App\Exceptions\MaxAllowedQuantityReachedException;
use App\Models\Product;
use App\Resources\Products\Customer\ProductResource;
use JsonSerializable;
use stdClass;

class CartItem extends stdClass implements JsonSerializable {
	/**
	 * @var integer
	 */
	protected $maxAllowedQuantity;

	/**
	 * @var integer
	 */
	protected $minAllowedQuantity;

	/**
	 * @var integer
	 */
	protected $key;

	/**
	 * @var string
	 */
	protected $uniqueId;

	/**
	 * @var integer
	 */
	protected $quantity;

	/**
	 * @var float
	 */
	protected $itemTotal;

	/**
	 * @var Product
	 */
	protected $product;

	/**
	 * @var \App\Models\Cart
	 */
	protected $cart;

	/**
	 * @var array
	 */
	protected $attributes;

	/**
	 * CartItem constructor.
	 * @param \App\Models\Cart $cart
	 * @param int $key
	 * @param array $attributes
	 */
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

	/**
	 * @return int
	 */
	public function getMaxAllowedQuantity(): int {
		return $this->maxAllowedQuantity;
	}

	/**
	 * @param int $maxAllowedQuantity
	 * @return CartItem
	 */
	protected function setMaxAllowedQuantity(int $maxAllowedQuantity): CartItem {
		$this->maxAllowedQuantity = $maxAllowedQuantity;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getMinAllowedQuantity(): int {
		return $this->minAllowedQuantity;
	}

	/**
	 * @param int $minAllowedQuantity
	 * @return CartItem
	 */
	protected function setMinAllowedQuantity(int $minAllowedQuantity): CartItem {
		$this->minAllowedQuantity = $minAllowedQuantity;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getItemTotal(): int {
		return $this->itemTotal;
	}

	/**
	 * @param float $itemTotal
	 * @return CartItem
	 */
	protected function setItemTotal(float $itemTotal): CartItem {
		$this->itemTotal = $itemTotal;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getKey(): int {
		return $this->key;
	}

	/**
	 * @param int $key
	 * @return CartItem
	 */
	public function setKey(int $key): CartItem {
		$this->key = $key;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getUniqueId(): string {
		return $this->uniqueId;
	}

	/**
	 * @param string $uniqueId
	 * @return CartItem
	 */
	public function setUniqueId(string $uniqueId): CartItem {
		$this->uniqueId = $uniqueId;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getQuantity(): int {
		return $this->quantity;
	}

	/**
	 * @param int $quantity
	 * @return CartItem
	 */
	public function setQuantity(int $quantity): CartItem {
		$this->quantity = $quantity;
		$this->itemTotal = $this->getApplicablePrice() * $this->getQuantity();
		return $this;
	}

	/**
	 * @return Product
	 */
	public function getProduct(): Product {
		return $this->product;
	}

	/**
	 * @param Product $product
	 * @return CartItem
	 */
	public function setProduct(Product $product): CartItem {
		$this->product = $product;
		return $this;
	}

	/**
	 * @return \App\Models\Cart
	 */
	public function getCart(): \App\Models\Cart {
		return $this->cart;
	}

	/**
	 * @param \App\Models\Cart $cart
	 * @return CartItem
	 */
	public function setCart(\App\Models\Cart $cart): CartItem {
		$this->cart = $cart;
		return $this;
	}

	/**
	 * @return array
	 */
	public function getAttributes(): array {
		return $this->attributes;
	}

	/**
	 * @param array $attributes
	 * @return CartItem
	 */
	public function setAttributes(array $attributes): CartItem {
		$this->attributes = $attributes;
		return $this;
	}

	/**
	 * Increases quantity of a product by a single unit or by specified number of units.
	 * @param int $incrementBy
	 * @throws MaxAllowedQuantityReachedException
	 */
	public function increaseQuantity(int $incrementBy = 1) {
		if ($this->getQuantity() < $this->maxAllowedQuantity) {
			if ($incrementBy > 1 && ($this->getQuantity() + $incrementBy) > $this->maxAllowedQuantity) $incrementBy = 1;
			$this->setQuantity($this->getQuantity() + 1);
		}
		else {
			throw new MaxAllowedQuantityReachedException($this);
		}
	}

	/**
	 * Decreases quantity of a product by a single unit, and additionally remove it
	 * from the cart if its quantity falls below minimum required.
	 */
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