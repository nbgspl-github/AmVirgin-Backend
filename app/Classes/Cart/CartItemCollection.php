<?php

namespace App\Classes\Cart;

class CartItemCollection {
	protected $items;

	public function __construct(\App\Models\Cart $cart, $items = []) {
		collect($items)->each(function ($item) use ($cart) {
			$item = (object)$item;
			$cartItem = new CartItem($cart, $item->key, $item->attributes);
			$cartItem->setKey($item->key);
			$cartItem->setQuantity($item->quantity);
			$this->setItem($cartItem->getUniqueId(), $cartItem);
		});
	}

	public function setItem(string $key, CartItem $item): CartItem {
		$this->items[$key] = $item;
		return $item;
	}

	public function deleteItem(string $key) {
		unset($this->items[$key]);
	}

	public function getItem(string $key): ?CartItem {
		return $this->items[$key];
	}

	public function has(string $key): bool {
		return isset($this->items[$key]);
	}

	public function iterate(callable $callback) {
		collect($this->items)->each($callback);
	}

	public function all() {
		return $this->items;
	}

	public function values() {
		return collect($this->items)->values();
	}
}