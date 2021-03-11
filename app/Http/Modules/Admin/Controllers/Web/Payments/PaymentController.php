<?php

namespace App\Http\Modules\Admin\Controllers\Web\Payments;

use App\Library\Utils\Extensions\Time;
use App\Models\Auth\Seller;
use App\Models\Models\SellerTransaction;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Carbon;

class PaymentController extends \App\Http\Modules\Admin\Controllers\Web\WebController
{
    public function __construct ()
    {
        parent::__construct();
    }

    public function index (): \Illuminate\Contracts\Support\Renderable
    {
        $payments = \App\Models\SellerPayment::query()->latest();
        return view('admin.payments.index')->with('payments',
            $this->paginateWithQuery($payments->whereHas('order', function (\Illuminate\Database\Eloquent\Builder $builder) {
                $builder->whereLike('number', $this->queryParameter());
            }))
        );
    }

    public function show (\App\Models\SellerPayment $payment)
    {
    }

    public function pay (): Renderable
    {
        $amount = 0;
        Seller::query()->where('last_payment_at', '<=', Carbon::now()->subDays(45)->format(Time::MYSQL_FORMAT))
            ->each(function (Seller $seller) use (&$amount) {
                $amount += $seller->payments()->whereNull('paid_at')->sum('total');
            });
        return view('admin.payments.ready')->with('amount', $amount);
    }

    public function process (Seller $seller)
    {
        $now = now()->format(Time::MYSQL_FORMAT);
        $payments = $seller->payments()->whereNull('paid_at');
        $amount = $payments->sum('total');
        $transaction = $this->initiate($seller, $amount);
        $payments->update([
            'transaction_id' => $transaction->id,
            'paid_at' => $now
        ]);
        $seller->update(['last_payment_at' => $now]);
    }

    protected function initiate (Seller $seller, $amount): ?SellerTransaction
    {
    }
}