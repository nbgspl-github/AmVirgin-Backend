<?php

namespace App\Console\Commands;

use App\Classes\Singletons\RazorpayClient;
use App\Library\Utils\Extensions\Time;
use App\Models\Auth\Seller;
use App\Models\Models\SellerTransaction;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Razorpay\Api\Api;

class PaymentCycle extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'p:p';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Processes & credits payment amounts calculated at the end of seller\'s 45 days billing cycle.';

    /**
     * @var Api
     */
    protected $client;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct ()
    {
        parent::__construct();
        $this->client = RazorpayClient::make();
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle (): void
    {
        Seller::query()->where('last_payment_at', '<=', Carbon::now()->subDays(45)->format(Time::MYSQL_FORMAT))
            ->each([$this, 'process']);
    }

    public function process (Seller $seller)
    {
        $now = now()->format(Time::MYSQL_FORMAT);
        $payments = $seller->payments()->whereNull('paid_at');
        $amount = $payments->sum('total');
        $transaction = $this->pay($seller, $amount);
        $payments->update([
            'transaction_id' => $transaction->id,
            'paid_at' => $now
        ]);
        $seller->update(['last_payment_at' => $now]);
    }

    protected function pay (Seller $seller, $amount): SellerTransaction
    {
        $rzpTransaction = (object)$this->client->order->create([
            'amount' => toAtomicAmount($amount),
            'currency' => 'INR'
        ]);
    }

    protected function createPlaceholderOrder ()
    {
    }
}
