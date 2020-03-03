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
		Settings::set('converted', strtotime($time));
		$converted = Settings::get('converted');
		echo date('H:i:s', $converted);
	}
}