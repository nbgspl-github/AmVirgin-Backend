<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Http\Resources\ConditionallyLoadsAttributes;

class CartTesterCommand extends Command {
	use ConditionallyLoadsAttributes;

	/**
	 * @var integer
	 */
	protected $x;

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
		collect(request()->file('abc'))->each(function ($item) {

		});
	}
}