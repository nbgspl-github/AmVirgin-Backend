<?php

namespace App\Models;

use App\Classes\Cart\CartItem;
use App\Classes\Cart\CartItemCollection;
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
	protected $itemCollection;

	public function __construct(array $attributes = []) {
		parent::__construct($attributes);
	}

	public static function retrieveThrows(string $sessionId, int $customerId = 0): self {
		if ($customerId == 0) {
			$model = self::where('sessionId', $sessionId)->firstOrFail();
			$model->loadModel();
			return $model;
		}
		else {
			echo 'With customer';
			$model = self::where('sessionId', $sessionId)->where('customerId', $customerId)->firstOrFail();
			$model->loadModel();
			return $model;
		}
	}

	public function loadModel() {
		$this->itemCollection = new CartItemCollection($this, jsonDecodeArray($this->attributes['items']));
	}

	public function addItem(CartItem ...$cartItem) {
		collect($cartItem)->each(function (CartItem $item) {
			$uniqueId = $item->getUniqueId();
			if (!$this->itemCollection->has($uniqueId))
				$this->itemCollection->setItem($uniqueId, $item)->increaseQuantity();
			else
				$this->itemCollection->getItem($uniqueId)->increaseQuantity();
		});
		$this->handleItemsUpdated();
	}

	public function removeItem(CartItem ...$cartItem) {
		collect($cartItem)->each(function (CartItem $item) {
			$uniqueId = $item->getUniqueId();
			if ($this->itemCollection->has($uniqueId))
				$this->itemCollection->getItem($uniqueId)->decreaseQuantity();
		});
		$this->handleItemsUpdated();
	}

	public function destroyItem(CartItem ...$cartItem) {
		collect($cartItem)->each(function (CartItem $item) {
			$this->itemCollection->deleteItem($item->getUniqueId());
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
		$items = $this->itemCollection->all();
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
				'items' => collect($items)->transform(function (CartItem $cartItem) {
					return $cartItem->render();
				})->values(),
			],
		];
	}

	public function save(array $options = []) {
		$this->items = $this->itemCollection->values()->transform(function (CartItem $cartItem) {
			return $cartItem->render();
		});
		return parent::save($options);
	}

	protected function handleItemsUpdated() {
		$this->resetCalculations();
		$this->itemCollection->iterate(function (CartItem $cartItem) {
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