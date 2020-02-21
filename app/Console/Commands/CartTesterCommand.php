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
		$array = [
			'a' => [
				'id' => 'a',
				'value' => 1,
			],
			'b' => [
				'id' => 'b',
				'value' => 2,
			],
			'c' => [
				'id' => 'c',
				'value' => 3,
			],
			'd' => [
				'id' => 'd',
				'value' => 4,
			],
			'e' => [
				'id' => 'e',
				'value' => 5,
			],
		];
	}
}