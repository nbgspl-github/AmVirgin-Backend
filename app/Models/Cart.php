<?php

namespace App\Models;

use App\Classes\Cart\CartItem;
use App\Classes\Cart\CartItemCollection;
use App\Exceptions\CartItemNotFoundException;
use App\Traits\RetrieveResource;
use Illuminate\Database\Eloquent\Model;
use stdClass;

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
	protected CartItemCollection $itemCollection;

	public function __construct(array $attributes = []) {
		parent::__construct($attributes);
	}

	public static function retrieve(string $sessionId, int $customerId = 0): self {
		if ($customerId == 0) {
			$model = self::where('sessionId', $sessionId)->first();
			$model->loadModel();
			return $model;
		}
		else {
			$model = self::where('sessionId', $sessionId)->where('customerId', $customerId)->first();
			$model->loadModel();
			return $model;
		}
	}

	public static function retrieveThrows(string $sessionId, int $customerId = 0): self {
		if ($customerId == 0) {
			$model = self::where('sessionId', $sessionId)->firstOrFail();
			$model->loadModel();
			return $model;
		}
		else {
			$model = self::where('sessionId', $sessionId)->where('customerId', $customerId)->firstOrFail();
			$model->loadModel();
			return $model;
		}
	}

	public function loadModel() {
		$decoded = jsonDecodeArray($this->items);
		$this->itemCollection = new CartItemCollection($this);
		$this->itemCollection->setItemsUpdatedCallback(function (array $items) {
			if (count($items) < 1)
				$this->items = jsonDecodeArray(jsonEncode(new stdClass()));
			else
				$this->items = $items;
		});
		if (count($decoded) > 0)
			$this->itemCollection->loadItems($decoded);
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
			else
				throw new CartItemNotFoundException($item);
		});
		$this->handleItemsUpdated();
	}

	public function destroyItem(CartItem ...$cartItem) {
		collect($cartItem)->each(function (CartItem $item) {
			$uniqueId = $item->getUniqueId();
			if ($this->itemCollection->has($uniqueId))
				$this->itemCollection->deleteItem($uniqueId);
			else
				throw new CartItemNotFoundException($item);
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
				'session' => $this->session->sessionId,
				'address' => null,
				'customer' => $this->customer,
				'itemCount' => $this->itemCount,
				'subTotal' => $this->subTotal,
				'tax' => $this->tax,
				'total' => $this->total,
				'paymentMode' => $this->paymentMode,
				'status' => $this->status,
				'items' => gettype($this->items) == 'array' ? array_values($this->items) : $this->items,
			],
		];
	}

	public function save(array $options = []) {
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