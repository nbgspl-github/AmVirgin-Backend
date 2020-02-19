<?php

namespace App\Classes\Cart;

use stdClass;

class Cart extends stdClass {

	/**
	 * Items available in the cart.
	 * @var array
	 */
	public $items = [];

	/**
	 * @var string
	 */
	protected $sessionId;

	/**
	 * @var integer
	 */
	protected $customerId;

	/**
	 * @var int
	 */
	protected $totalQuantity = 0;

	/**
	 * @var \App\Models\Cart
	 */
	protected $model;

	public function __construct(string $sessionId, int $customerId = 0) {
		$this->model = \App\Models\Cart::retrieveThrows($sessionId, $customerId);
		$this->sessionId = $sessionId;
		$this->customerId = $customerId;
	}

	public function getSessionId(): string {
		return $this->sessionId;
	}

	public function setSessionId(string $sessionId): Cart {
		$this->sessionId = $sessionId;
		return $this;
	}

	public function addItem(CartItem ...$cartItem) {
		collect($cartItem)->each(function (CartItem $item) {
			if (!isset($this->items[$item->getItemIdentifier()]))
				$this->items[$item->getItemIdentifier()] = $item;
			else {
				$cartItem = $this->items[$item->getItemIdentifier()];
				$cartItem->increaseQuantity();
			}
		});
		$this->handleItemsUpdated();
	}

	public function removeItem(CartItem ...$cartItem) {
		collect($cartItem)->each(function (CartItem $item) {
			unset($this->items[$item->getItemIdentifier()]);
		});
		$this->handleItemsUpdated();
	}

	public function save() {

	}

	protected function handleItemsUpdated() {
		$this->resetCalculations();
		collect($this->items)->each(function (CartItem $cartItem) {
			$this->addToTotalQuantity($cartItem);
			$this->calculateTaxesAndTotals($cartItem);
		});
	}

	protected function resetCalculations() {

	}

	protected function addToTotalQuantity(CartItem $cartItem) {

	}

	protected function calculateTaxesAndTotals(CartItem $cartItem) {

	}

	protected function triggerUpdateModel() {

	}
}