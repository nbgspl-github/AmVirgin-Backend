<?php

namespace App\Classes\Cart;

use App\Exceptions\MaxAllowedQuantityReachedException;
use App\Models\Product;
use stdClass;

class CartItem extends stdClass {
	/**
	 * Maximum number of units of a product that can be added to a single cart.
	 */
	const MaxAllowedQuantity = 10;

	/**
	 * Minimum number of units required of a product in cart. If the count reaches below this, the item
	 * should be removed automatically.
	 */
	const MinAllowedQuantity = 1;

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
	}

	public static function fromArray(array $data, Cart $cart): self {
		$self = new self($cart, Product::retrieve($data['product']), $data['attributes']);
		$self->setQuantity($data['quantity']);
		$self->decreaseQuantity();
		return $self;
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
		if ($this->getQuantity() < self::MaxAllowedQuantity) {
			if ($incrementBy > 1 && ($this->getQuantity() + $incrementBy) > self::MaxAllowedQuantity) $incrementBy = 1;
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
		if ($this->getQuantity() > self::MinAllowedQuantity)
			$this->setQuantity($this->getQuantity() - 1);
		else {
			$this->removeSelf();
		}
	}

	public function toArray(): array {
		return [
			'MaxAllowedQuantity' => self::MaxAllowedQuantity,
			'MinAllowedQuantity' => self::MinAllowedQuantity,
			'itemId' => $this->getItemId(),
			'itemIdentifier' => $this->getItemIdentifier(),
			'product' => $this->getProduct()->getKey(),
			'quantity' => $this->getQuantity(),
			'attributes' => $this->getAttributes(),
		];
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
	 * Removes instance of this item from the cart.
	 */
	protected function removeSelf() {
		$this->cart->removeItem($this);
	}
}