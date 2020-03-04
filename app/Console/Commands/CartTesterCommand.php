<?php

namespace App\Console\Commands;

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
		echo strtotime('1970-01-01 ' . $time);
//		$split = explode(':', $time);
//		$final = 0;
//		$final += ($split[0] * 60 * 60);
//		$final += ($split[1] * 60);
//		$final += ($split[2]);
//		echo $final * 1000;
	}
}