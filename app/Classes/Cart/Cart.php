<?php

namespace App\Classes\Cart;

use App\Models\CartItemZ;
use App\Resources\Cart\CartResource;
use stdClass;

class Cart extends stdClass {
	const TaxRate = 2.5;

	/**
	 * Items available in the cart.
	 * @var array
	 */
	public $items = [];

	/**
	 * @var \App\Models\Cart
	 */
	protected $model;

	public function __construct(string $sessionId, int $customerId = 0) {
		$this->model = \App\Models\Cart::retrieveThrows($sessionId, $customerId);
		$this->model->customerId = $customerId;
		collect($this->model->items()->get()->all())->each(function (\App\Models\CartItemZ $cartItem) {
			$cartItem->setCart($this);
			$this->items[$cartItem->getUniqueId()] = $cartItem;
		});
	}

	public function getSessionId(): string {
		return $this->model->sessionId;
	}

	public function getCartId(): int {
		return $this->model->getKey();
	}

	public function addItem(CartItemZ ...$cartItem) {
		collect($cartItem)->each(function (CartItemZ $item) {
			if (!isset($this->items[$item->getUniqueId()])) {
				$item->save();
				$this->items[$item->getUniqueId()] = $item;
			}
			else {
				$cartItem = $this->items[$item->getUniqueId()];
				$cartItem->increaseQuantity();
			}
		});
		$this->handleItemsUpdated();
	}

	public function removeItem(CartItemZ ...$cartItem) {
		collect($cartItem)->each(function (CartItemZ $item) {
			if (isset($this->items[$item->getUniqueId()])) {
				$cartItem = $this->items[$item->getUniqueId()];
				$cartItem->decreaseQuantity();
			}
		});
		$this->handleItemsUpdated();
	}

	public function destroyItem(CartItemZ ...$cartItem) {
		collect($cartItem)->each(function (CartItemZ $item) {
			unset($this->items[$item->getUniqueId()]);
			$item->delete();
		});
		$this->handleItemsUpdated();
	}

	public function update() {
		$this->model->save();
	}

	public function render() {
		$this->model->save();
		return new CartResource($this->model);
	}

	protected function handleItemsUpdated() {
		$this->resetCalculations();
		collect($this->items)->each(function (CartItemZ $cartItem) {
			$this->addToTotalQuantity($cartItem);
			$this->calculateSubtotals($cartItem);
		});
		$this->calculateGrandTotalsAndTax();
		$this->update();
	}

	protected function resetCalculations() {
		$this->model->tax = 0.0;
		$this->model->itemCount = 0;
		$this->model->subTotal = 0.0;
		$this->model->total = 0;
	}

	protected function addToTotalQuantity(CartItemZ $cartItem) {
		$this->model->itemCount += $cartItem->getQuantity();
	}

	protected function calculateSubtotals(CartItemZ $cartItem) {
		$this->model->subTotal += $cartItem->getItemTotal();
	}

	protected function calculateGrandTotalsAndTax() {
		$this->model->tax = (float)(self::TaxRate * $this->model->subTotal);
		$this->model->total = $this->model->tax + $this->model->subTotal;
	}
}