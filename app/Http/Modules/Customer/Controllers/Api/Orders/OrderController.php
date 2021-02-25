<?php

namespace App\Http\Modules\Customer\Controllers\Api\Orders;

use App\Exceptions\ActionNotAllowedException;
use App\Models\Order\Order;
use App\Resources\Orders\Customer\ListResource;
use App\Resources\Orders\Customer\OrderResource;
use Illuminate\Http\JsonResponse;

class OrderController extends \App\Http\Modules\Customer\Controllers\Api\ApiController
{
    public function __construct ()
    {
        parent::__construct();
    }

    public function index (): JsonResponse
    {
        return responseApp()->prepare(
            ListResource::collection(
                $this->customer()->orders()->paginate($this->paginationChunk())
            )->response()->getData(),
        );
    }

    public function show (Order $order): JsonResponse
    {
        if ($order->customer != null && $order->customer->is($this->customer()))
            return responseApp()->prepare(
                new OrderResource($order)
            );
        throw new ActionNotAllowedException();
    }

    public function track (Order $order): JsonResponse
    {
        if ($order->customer != null && $order->customer->is($this->customer()))
            return responseApp()->prepare(
                new OrderResource($order)
            );
        throw new ActionNotAllowedException();
    }
}