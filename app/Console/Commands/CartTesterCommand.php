<?php

namespace App\Console\Commands;

use App\Classes\Time;
use App\Models\Seller;
use App\Models\Settings;
use App\Models\ShopSlider;
use Illuminate\Console\Command;
use Illuminate\Http\Resources\ConditionallyLoadsAttributes;

class CartTesterCommand extends Command {
	use ConditionallyLoadsAttributes;

	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'cart:test';

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
	public function __construct() {
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 * @return mixed
	 */
	public function handle() {
		$time = Settings::get('time');
		$elapsed = \time();
		$lastUpdated = Settings::getInt('shopSaleOfferDetailsUpdated', 0);
		$remaining = $lastUpdated - Time::toSeconds($time);
		$countDown = 0;
		if ($elapsed >= $remaining) {
			$countDown = abs($remaining - $elapsed);
			$countDown *= 1000;
		}
		echo sprintf('Elapsed = %d, remaining = %d', $elapsed, $remaining);
	}
}