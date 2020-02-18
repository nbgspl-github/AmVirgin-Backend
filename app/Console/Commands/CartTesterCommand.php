<?php

namespace App\Console\Commands;

use App\Classes\Cart\Cart;
use App\Classes\Cart\CartItem;
use App\Exceptions\MaxAllowedQuantityReachedException;
use App\Models\Product;
use Illuminate\Console\Command;
use Sujip\Guid\Facades\Guid;

class CartTesterCommand extends Command {
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
	 *
	 * @return mixed
	 */
	public function handle() {

	}
}