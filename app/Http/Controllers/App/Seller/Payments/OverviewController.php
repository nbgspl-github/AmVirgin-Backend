<?php


namespace App\Http\Controllers\App\Seller\Payments;


use App\Enums\Seller\OrderStatus;
use App\Models\SellerOrder;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Carbon;

class OverviewController extends \App\Http\Controllers\Web\ExtendedResourceController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function show(): JsonResponse
    {
        $response = responseApp();
        try {
            $payload = [
                'next' => [
                    'postpaid' => 0.0,
                    'prepaid' => 0.0,
                    'total' => 0.0
                ],
                'previous' => [
                    'postpaid' => 0.0,
                    'prepaid' => 0.0,
                    'total' => 0.0
                ],
                'total' => [
                    'postpaid' => 0.0,
                    'prepaid' => 0.0,
                    'total' => 0.0
                ]
            ];
            $current = Carbon::now()->timestamp;
            $orderCollection = SellerOrder::startQuery()->useAuth()->withRelations('order')->withinCurrentMonth()->status((new OrderStatus(OrderStatus::Delivered)))->get();
            $orderCollection->each(function (SellerOrder $sellerOrder) use ($current, &$payload) {
                if ($sellerOrder->order()->exists()) {
                    $order = $sellerOrder->order;
                    if ($order->paymentMode == 'cash-on-delivery') {
                        $payload['total']['postpaid'] += $order->total;
                    } else {
                        $payload['total']['prepaid'] += $order->total;
                    }
                    if (strtotime($order->created_at) < $current) {
                        if ($order->paymentMode == 'cash-on-delivery') {
                            $payload['previous']['postpaid'] += $order->total;
                        } else {
                            $payload['previous']['prepaid'] += $order->total;
                        }
                    } else {
                        if ($order->paymentMode == 'cash-on-delivery') {
                            $payload['next']['postpaid'] += $order->total;
                        } else {
                            $payload['next']['prepaid'] += $order->total;
                        }
                    }
                }
            });
            $response->status(HttpOkay)->message('Payment overview details retrieved successfully.')->setValue('payload', $payload);
        } catch (\Throwable $exception) {
            $response->status(HttpOkay)->message($exception->getMessage());
        } finally {
            return $response->send();
        }
    }
    public function totalSales(): JsonResponse
    {
        $response = responseApp();
        try {
            $today = Carbon::today(); 
            $current = Carbon::now()->timestamp; 
            $orderC = SellerOrder::startQuery()->useAuth()->withRelations('order');
            if (!empty(request('days'))) {
                $orderC->useWhere('created_at', '>=', $today->subDays(request('days')));
            }            
            $orderCollection = $orderC->get(); 
            $datSet=array();
            if (!empty(count($orderCollection))) {
                    $i = 0; 
                    $datSet['salesInUnit']=count($orderCollection);
                    $salesInRupee=0;
                   foreach ($orderCollection as $key => $value) {
                       $salesInRupee += $value->order->total; 
                   } 
                $datSet['salesInRupees']=$salesInRupee;
                }    
            $response->status(HttpOkay)->message('Sales in last '.request('days').' days retrieved successfully.')->setValue('payload', $datSet);
        } catch (\Throwable $exception) {
            $response->status(HttpOkay)->message($exception->getMessage());
        } finally {
            return $response->send();
        }
    }

    protected function guard()
    {
        return auth(self::SellerAPI);
    }
}