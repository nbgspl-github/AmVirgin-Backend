<?php

namespace App\Classes\Cart;

use App\Constants\OfferTypes;
use App\Exceptions\MaxAllowedQuantityReachedException;
use App\Exceptions\OutOfStockException;
use App\Models\Cart;
use App\Models\Product;
use App\Resources\Cart\CartItemResource;
use JsonSerializable;
use stdClass;
use Symfony\Component\HttpFoundation\ServerBag;

class CartItem extends stdClass implements JsonSerializable{
	protected int $maxAllowedQuantity;

	protected int $minAllowedQuantity;

	protected int $key;

	protected string $uniqueId;

	protected int $quantity;

	protected float $itemTotal;

	protected Product $product;

	protected Cart $cart;

	public function __construct(Cart $cart, int $key){
		$this->setKey($key);
		$this->setProduct(Product::find($this->getKey()));
		$this->setMinAllowedQuantity(1);
		$this->setMaxAllowedQuantity($this->getCalculatedMaxAllowedQuantity());
		$this->setCart($cart);
		$this->setQuantity(0);
		$this->setUniqueId(sprintf('%s-%d', $cart->sessionId, $key));
		if ($this->getProduct()->isOutOfStock())
			throw new OutOfStockException();
	}

	public function getMaxAllowedQuantity(): int{
		return $this->maxAllowedQuantity;
	}

	public function getCalculatedMaxAllowedQuantity(): int{
		$actualMaxAllowed = $this->getProduct()->maxQuantityPerOrder();
		$inStock = $this->getProduct()->stock();
		if ($inStock >= $actualMaxAllowed)
			return $actualMaxAllowed;
		else
			return $inStock;
	}

	protected function setMaxAllowedQuantity(int $maxAllowedQuantity): CartItem{
		$this->maxAllowedQuantity = $maxAllowedQuantity;
		return $this;
	}

	public function getMinAllowedQuantity(): int{
		return $this->minAllowedQuantity;
	}

	protected function setMinAllowedQuantity(int $minAllowedQuantity): CartItem{
		$this->minAllowedQuantity = $minAllowedQuantity;
		return $this;
	}

	public function getItemTotal(): int{
		return $this->itemTotal;
	}

	protected function setItemTotal(float $itemTotal): CartItem{
		$this->itemTotal = $itemTotal;
		return $this;
	}

	public function getKey(): int{
		return $this->key;
	}

	public function setKey(int $key): CartItem{
		$this->key = $key;
		return $this;
	}

	public function getUniqueId(): string{
		return $this->uniqueId;
	}

	public function setUniqueId(string $uniqueId): CartItem{
		$this->uniqueId = $uniqueId;
		return $this;
	}

	public function getQuantity(): int{
		return $this->quantity;
	}

	public function setQuantity(int $quantity): CartItem{
		$this->quantity = $quantity;
		$this->itemTotal = $this->getApplicablePrice() * $this->getQuantity();
		return $this;
	}

	public function getProduct(): Product{
		return $this->product;
	}

	public function setProduct(Product $product): CartItem{
		$this->product = $product;
		return $this;
	}

	public function getCart(): Cart{
		return $this->cart;
	}

	public function setCart(Cart $cart): CartItem{
		$this->cart = $cart;
		return $this;
	}

	public function increaseQuantity(int $incrementBy = 1){
		if ($this->getQuantity() < $this->maxAllowedQuantity) {
			if ($incrementBy > 1 && ($this->getQuantity() + $incrementBy) > $this->maxAllowedQuantity) $incrementBy = 1;
			$this->setQuantity($this->getQuantity() + 1);
		}
		else {
			throw new MaxAllowedQuantityReachedException($this);
		}
	}

	public function decreaseQuantity(){
		if ($this->getQuantity() > $this->minAllowedQuantity)
			$this->setQuantity($this->getQuantity() - 1);
		else {
			$this->removeSelf();
		}
	}

	public function jsonSerialize(){
		return [
			'key' => $this->getKey(),
			'product' => new CartItemResource($this->getProduct()),
			'quantity' => $this->getQuantity(),
			'uniqueId' => $this->getUniqueId(),
			'itemTotal' => $this->getItemTotal(),
		];
	}

	public function getApplicablePrice(): float{
		return $this->product->sellingPrice();
	}

	protected function removeSelf(){
		$this->cart->destroyItem($this);
	}
}