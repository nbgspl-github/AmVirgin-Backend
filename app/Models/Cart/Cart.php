<?php

namespace App\Models\Cart;

use App\Classes\Cart\CartItem;
use App\Classes\Cart\CartItemCollection;
use App\Exceptions\CartAlreadySubmittedException;
use App\Exceptions\CartItemNotFoundException;
use App\Library\Database\Eloquent\Model;
use App\Library\Enums\Cart\Status;
use App\Library\Utils\Extensions\Str;
use App\Models\Auth\Customer;
use App\Models\Order\Order;
use App\Models\Order\SubOrder;
use App\Models\Order\Transaction;
use App\Resources\Products\Customer\OptionResource;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use stdClass;

class Cart extends \App\Library\Database\Eloquent\Model
{
    const TaxRate = 0.25;
    protected $guarded = ['id'];
    protected ?CartItemCollection $itemCollection;

    public function __construct (array $attributes = [])
    {
        parent::__construct($attributes);
    }

    public static function retrieve (string $sessionId, int $customerId = 0): self
    {
        if ($customerId == 0) {
            $model = self::where('sessionId', $sessionId)->first();
            $model->loadModel();
            return $model;
        } else {
            $model = self::where('sessionId', $sessionId)->where('customerId', $customerId)->first();
            $model->loadModel();
            return $model;
        }
    }

    public static function retrieveThrows (string $sessionId, int $customerId = 0): self
    {
        if ($customerId == 0) {
            $model = self::where('sessionId', $sessionId)->firstOrFail();
            $model->loadModel();
            return $model;
        } else {
            $model = self::where('sessionId', $sessionId)->where('customerId', $customerId)->firstOrFail();
            $model->loadModel();
            return $model;
        }
    }

    public function loadModel ()
    {
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

    public function addItem (CartItem ...$cartItems)
    {
        if ($this->wasSubmitted()) {
            throw new CartAlreadySubmittedException($this);
        }
        collect($cartItems)->each(function (CartItem $item) {
            $uniqueId = $item->getUniqueId();
            if (!$this->itemCollection->has($uniqueId))
                $this->itemCollection->setItem($uniqueId, $item)->increaseQuantity();
            else
                $this->itemCollection->getItem($uniqueId)->increaseQuantity();
        });
        $this->handleItemsUpdated();
    }

    public function updateItem (CartItem ...$cartItems)
    {
        if ($this->wasSubmitted()) {
            throw new CartAlreadySubmittedException($this);
        }
        collect($cartItems)->each(function (CartItem $item) {
            $uniqueId = $item->getUniqueId();
            if (!$this->itemCollection->has($uniqueId))
                $this->itemCollection->setItem($uniqueId, $item)->setQuantity($item->getQuantity());
            else
                $this->itemCollection->getItem($uniqueId)->setQuantity($item->getQuantity());
        });
        $this->handleItemsUpdated();
    }

    public function removeItem (CartItem ...$cartItems)
    {
        if ($this->wasSubmitted()) {
            throw new CartAlreadySubmittedException($this);
        }
        collect($cartItems)->each(function (CartItem $item) {
            $uniqueId = $item->getUniqueId();
            if ($this->itemCollection->has($uniqueId))
                $this->itemCollection->getItem($uniqueId)->decreaseQuantity();
            else
                throw new CartItemNotFoundException();
        });
        $this->handleItemsUpdated();
    }

    public function destroyItem (CartItem ...$cartItems)
    {
        if ($this->wasSubmitted()) {
            throw new CartAlreadySubmittedException($this);
        }
        collect($cartItems)->each(function (CartItem $item) {
            $uniqueId = $item->getUniqueId();
            if ($this->itemCollection->has($uniqueId))
                $this->itemCollection->deleteItem($uniqueId);
            else
                throw new CartItemNotFoundException();
        });
        $this->handleItemsUpdated();
    }

    public function contains (CartItem $item)
    {
        $uniqueId = $item->getUniqueId();
        return $this->itemCollection->has($uniqueId);
    }

    public function session ()
    {
        return $this->hasOne(\App\Models\Cart\Session::class, 'sessionId', 'sessionId');
    }

    public function customer ()
    {
        return $this->hasOne(Customer::class, 'id', 'customerId');
    }

    public function render ()
    {
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
                'items' => gettype($this->items) == 'array' ? array_values($this->items) : jsonDecodeArray($this->items),
            ],
        ];
    }

    public function save (array $options = [])
    {
        return parent::save($options);
    }

    /**
     * @param Transaction $transaction
     * @return Order|Model
     * @throws CartAlreadySubmittedException
     */
    public function submit (Transaction $transaction): Order
    {
        /**
         * 1.) WE create the main order which will act as the parent order for all the subsequent vendor orders.
         * 2.) Then we collect products according to vendors and consolidate them into separate vendor orders.
         */

        if ($this->wasSubmitted()) {
            throw new CartAlreadySubmittedException($this);
        }
        /**
         * @var $order Order
         */
        $order = Order::query()->create([
            'customerId' => $this->customerId,
            'transactionId' => $transaction->id,
            'addressId' => $this->addressId,
            'billingAddressId' => $this->billingAddressId,
            'paymentMode' => $this->paymentMode,
            'quantity' => $this->itemCount,
            'subTotal' => $this->subTotal,
            'tax' => $this->tax,
            'total' => $this->total,
        ]);
        $consolidated = $this->itemCollection->items()->groupBy(function (CartItem $item, string $key) use (&$order) {
            return $item->getProduct()->sellerId;
        });
        $consolidated->each(function (Collection $items, string $sellerId) use (&$order) {
            $subTotal = $items->sum(function (CartItem $item) {
                return $item->getItemTotal();
            });
            $total = $subTotal;
            /**
             * @var $subOrder SubOrder
             */
            $subOrder = $order->subOrders()->create([
                'sellerId' => $sellerId,
                'customerId' => $order->customerId,
                'quantity' => $items->sum(function (CartItem $item) {
                    return $item->getQuantity();
                }),
                'tax' => 0,
                'subTotal' => $subTotal,
                'total' => $total,
            ]);
            $items->each(function (CartItem $item) use ($sellerId, $subOrder, $order) {
                $subOrder->items()->create([
                    'orderId' => $order->id,
                    'sellerId' => $sellerId,
                    'productId' => $item->getProduct()->id,
                    'quantity' => $item->getQuantity(),
                    'price' => $item->getApplicablePrice(),
                    'originalPrice' => $item->getProduct()->originalPrice,
                    'sellingPrice' => $item->getProduct()->sellingPrice,
                    'subTotal' => $item->getItemTotal(),
                    'total' => $item->getItemTotal(),
                    'returnable' => $item->getProduct()->returnable,
                    'returnPeriod' => $item->getProduct()->returnPeriod,
                    'returnValidUntil' => $item->getProduct()->returnable ? Carbon::now()->addDays($item->getProduct()->returnPeriod) : null,
                    'returnType' => $item->getProduct()->returnType ?? 'refund',
                    'options' => OptionResource::collection($item->getProduct()->options),
                ]);
            });
        });
        $this->save();
        return $order;
    }

    public function wasSubmitted ()
    {
        return Str::equals($this->status, Status::Submitted);
    }

    public function transaction ()
    {
        return $this->belongsTo(Transaction::class, 'transactionId');
    }

    protected function handleItemsUpdated ()
    {
        $this->resetCalculations();
        $this->itemCollection->iterate(function (CartItem $cartItem) {
            $this->addToTotalQuantity($cartItem);
            $this->calculateSubtotals($cartItem);
        });
        $this->calculateGrandTotalsAndTax();
        $this->save();
    }

    protected function resetCalculations ()
    {
        $this->tax = 0.0;
        $this->itemCount = 0;
        $this->subTotal = 0.0;
        $this->total = 0;
    }

    protected function addToTotalQuantity (CartItem $cartItem)
    {
        $this->itemCount += $cartItem->getQuantity();
    }

    protected function calculateSubtotals (CartItem $cartItem)
    {
        $this->subTotal += $cartItem->getItemTotal();
    }

    protected function calculateGrandTotalsAndTax ()
    {
        $this->tax = (float)(self::TaxRate * $this->subTotal);
        $this->total = $this->tax + $this->subTotal;
    }
}