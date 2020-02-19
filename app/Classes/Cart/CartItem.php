<?php

namespace App\Classes\Cart;

use App\Exceptions\MaxAllowedQuantityReachedException;
use App\Models\Product;
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
	protected $itemId;

	/**
	 * @var string
	 */
	protected $itemIdentifier;

	/**
	 * @var integer
	 */
	protected $quantity = 0;

	/**
	 * @var Product
	 */
	protected $product;

	/**
	 * @var Cart
	 */
	protected $cart;

	/**
	 * @var array
	 */
	protected $attributes = [];

	/**
	 * CartItem constructor.
	 * @param Cart $cart
	 * @param Product $product
	 * @param array $attributes
	 * @throws MaxAllowedQuantityReachedException
	 */
	public function __construct(Cart $cart, Product $product, $attributes = []) {
		$this->setProduct($product);
		$this->setItemId($product->getKey());
		$this->setQuantity(0);
		$this->setItemIdentifier(sprintf('%s-%d', $cart->getSessionId(), $product->getKey()));
		$this->setAttributes($attributes);
		$this->setCart($cart);
		$this->increaseQuantity();
		$this->minAllowedQuantity = \App\Models\CartItem::MinAllowedQuantity;
		$this->minAllowedQuantity = \App\Models\CartItem::MaxAllowedQuantity;
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
	protected function setAttributes(array $attributes): CartItem {
		$this->attributes = $attributes;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getItemId(): int {
		return $this->itemId;
	}

	/**
	 * @param int $itemId
	 * @return CartItem
	 */
	protected function setItemId(int $itemId): CartItem {
		$this->itemId = $itemId;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getItemIdentifier(): string {
		return $this->itemIdentifier;
	}

	/**
	 * @param string $itemIdentifier
	 * @return CartItem
	 */
	protected function setItemIdentifier(string $itemIdentifier): CartItem {
		$this->itemIdentifier = $itemIdentifier;
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
	 * @return self
	 */
	public function setQuantity(int $quantity): self {
		$this->quantity = $quantity;
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

	/**
	 * @param Cart $cart
	 * @return CartItem
	 */
	public function setCart(Cart $cart): CartItem {
		$this->cart = $cart;
		return $this;
	}

	/**
	 * @return array|mixed
	 */
	public function jsonSerialize() {
		return [
			'key' => $this->getItemId(),
			'identifier' => $this->getItemIdentifier(),
			'quantity' => $this->getQuantity(),
			'details' => $this->getProduct()->toArray(),
		];
	}

	public function save() {

	}

	/**
	 * Removes instance of this item from the cart.
	 */
	protected function removeSelf() {
		$this->cart->removeItem($this);
	}
}