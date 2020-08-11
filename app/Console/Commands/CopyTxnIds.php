<?php

namespace App\Console\Commands;

use App\Models\SellerOrder;
use Illuminate\Console\Command;

class CopyTxnIds extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'copy:txnId';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        SellerOrder::query()->with('order')->get()->each(function (SellerOrder $sellerOrder) {
            if ($sellerOrder->order()->exists()) {
                $sellerOrder->update([
                    'neftId' => str_replace("pay_", "", $sellerOrder->order->transactionId)
                ]);
            }
        });
    }
}
