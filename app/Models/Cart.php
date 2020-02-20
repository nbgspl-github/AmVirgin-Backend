<?php

namespace App\Models;

use App\Classes\Cart\CartItem;
use App\Traits\RetrieveResource;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model {
	use RetrieveResource;
	const TaxRate = 0.25;
	protected $table = 'carts';
	protected $fillable = [
		'sessionId',
		'addressId',
		'customerId',
		'items',
		'itemCount',
		'subTotal',
		'tax',
		'paymentMode',
		'status',
	];
	protected $cartItems = [];

	public function __construct(array $attributes = []) {
		parent::__construct($attributes);
		collect(jsonDecode($this->items))->each(function ($item) {
			$item = (object)$item;
			$cartItem = new CartItem($this, $item->key, $item->attributes);
			$cartItem->setUniqueId($item->uniqueId);
			$cartItem->setQuantity($item->quantity);
			$this->cartItems[$cartItem->getUniqueId()] = $cartItem;
		});
	}

	public static function retrieveThrows(string $sessionId, int $customerId = 0) {
		if ($customerId == 0)
			return self::where('sessionId', $sessionId)->firstOrFail();
		else
			return self::where('sessionId', $sessionId)->where('customerId', $customerId)->firstOrFail();
	}

	public function getItemsAttribute() {
		return jsonDecode($this->items);
	}

	public function setItemsAttribute(array $items = []) {
		$this->items = jsonEncode($items);
	}

	public function addItem(CartItem ...$cartItem) {
		collect($cartItem)->each(function (CartItem $item) {
			if (!isset($this->cartItems[$item->getUniqueId()])) {
				$item->increaseQuantity();
				$this->cartItems[$item->getUniqueId()] = $item;
			}
			else {
				$cartItem = $this->cartItems[$item->getUniqueId()];
				$cartItem->increaseQuantity();
			}
		});
		$this->handleItemsUpdated();
	}

	public function removeItem(CartItem ...$cartItem) {
		collect($cartItem)->each(function (CartItem $item) {
			if (isset($this->cartItems[$item->getUniqueId()])) {
				$cartItem = $this->cartItems[$item->getUniqueId()];
				$cartItem->decreaseQuantity();
			}
		});
		$this->handleItemsUpdated();
	}

	public function destroyItem(CartItem ...$cartItem) {
		collect($cartItem)->each(function (CartItem $item) {
			unset($this->cartItems[$item->getUniqueId()]);
			$item->delete();
		});
		$this->handleItemsUpdated();
	}

	public function session() {
		return $this->hasOne(CartSession::class, 'sessionId', 'sessionId');
	}

	public function customer() {
		return $this->hasOne(Customer::class, 'id', 'customerId');
	}

	public function render() {
		return [
			'cart' => [
				'session' => $this->session,
				'address' => null,
				'customer' => $this->customer,
				'itemCount' => $this->itemCount,
				'subTotal' => $this->subTotal,
				'tax' => $this->tax,
				'total' => $this->total,
				'paymentMode' => $this->paymentMode,
				'status' => $this->status,
				'items' => collect($this->cartItems)->transform(function (CartItem $cartItem) {
					return $cartItem->render();
				})->values(),
			],
		];
	}

	public function save(array $options = []) {
		$this->items = $this->cartItems;
		return parent::save($options);
	}

	protected function handleItemsUpdated() {
		$this->resetCalculations();
		collect($this->cartItems)->each(function (CartItem $cartItem) {
			$this->addToTotalQuantity($cartItem);
			$this->calculateSubtotals($cartItem);
		});
		$this->calculateGrandTotalsAndTax();
		$this->save();
	}

	protected function resetCalculations() {
		$this->tax = 0.0;
		$this->itemCount = 0;
		$this->subTotal = 0.0;
		$this->total = 0;
	}

	protected function addToTotalQuantity(CartItem $cartItem) {
		$this->itemCount += $cartItem->getQuantity();
	}

	protected function calculateSubtotals(CartItem $cartItem) {
		$this->subTotal += $cartItem->getItemTotal();
	}

	protected function calculateGrandTotalsAndTax() {
		$this->tax = (float)(self::TaxRate * $this->subTotal);
		$this->total = $this->tax + $this->subTotal;
	}
}