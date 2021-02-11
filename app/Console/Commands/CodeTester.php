<?php

namespace App\Console\Commands;

use App\Classes\Singletons\RazorpayClient;
use Illuminate\Console\Command;

class CodeTester extends Command
{
	use \App\Traits\NotifiableViaSms;

	const TwoHours = 7200;

	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'c:t';

	protected $mobile = "8375976617";

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Command description';

	protected \Razorpay\Api\Api $client;

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

	public function handle () : bool
	{
		//amvirgin.proximitycrm.com/api/customer/subscriptions/checkout?transactionId=405&paymentId=pay_GaDzDA0Zmge0sO&signature=5c687aa750d9fc216fc77ba29acde07676d57947b6e5b9283608b2297f85e11e&orderId=order_GaDyKvJRi72Aud
		$signature = "5c687aa750d9fc216fc77ba29acde07676d57947b6e5b9283608b2297f85e11e";
		$paymentId = "pay_GaDzDA0Zmge0sO";
		$orderId = "order_GaDyKvJRi72Aud";
		try {
			$this->client->utility->verifyPaymentSignature([
				'razorpay_signature' => $signature,
				'razorpay_payment_id' => $paymentId,
				'razorpay_order_id' => $orderId
			]);
			echo "Success";
		} catch (\Razorpay\Api\Errors\SignatureVerificationError $e) {
			echo "Failed";
		}
	}
}