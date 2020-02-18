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
	 * @var int
	 */
	protected $totalQuantity = 0;

	/**
	 * Cart constructor.
	 * @param string $sessionId
	 */
	public function __construct(string $sessionId) {
		$this->sessionId = $sessionId;
	}

	/**
	 * @return string
	 */
	public function getSessionId(): string {
		return $this->sessionId;
	}

	/**
	 * @param string $sessionId
	 * @return Cart
	 */
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

	public function toArray(): array {
		return [
			'sessionId' => $this->getSessionId(),
			'items' => collect($this->items)->transform(function (CartItem $cartItem) {
				return $cartItem->toArray();
			})->all(),
		];
	}

	public function fromArray(array $data): self {
		$self = new self($data['sessionId']);
		collect($data['items'])->each(function ($item) {
			$this->items[] = CartItem::fromArray($item, $this);
		});
		return $self;
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
}