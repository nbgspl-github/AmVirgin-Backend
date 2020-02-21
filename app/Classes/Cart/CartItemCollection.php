<?php

namespace App\Classes\Cart;

class CartItemCollection {
	protected $items;
	protected $cart;
	protected $callback;

	public function __construct(\App\Models\Cart $cart) {
		$this->cart = $cart;
		$this->items = [];
	}

	public function loadItems(array $items = []) {
		collect($items)->each(function ($item, $key) {
			$item = (object)$item;
			$cartItem = new CartItem($this->cart, $item->key, $item->attributes);
			$cartItem->setKey($item->key);
			$cartItem->setQuantity($item->quantity);
			$cartItem->setUniqueId($key);
			$this->setItem($key, $cartItem);
		});
	}

	public function setItem(string $key, CartItem $item): CartItem {
		$this->items[$key] = $item;
		call_user_func($this->callback, $this->items);
		return $item;
	}

	public function deleteItem(string $key) {
		unset($this->items[$key]);
		call_user_func($this->callback, $this->items);
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

	public function setItemsUpdatedCallback(callable $callback) {
		$this->callback = $callback;
	}
}