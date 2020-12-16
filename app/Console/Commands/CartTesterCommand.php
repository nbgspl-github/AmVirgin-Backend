<?php

namespace App\Console\Commands;

use App\Http\Controllers\App\Customer\CatalogController;
use App\Traits\ValidatesRequest;
use Illuminate\Console\Command;
use Illuminate\Http\Resources\ConditionallyLoadsAttributes;
use Sujip\Guid\Facades\Guid;

class CartTesterCommand extends Command{
	use ConditionallyLoadsAttributes;
	use ValidatesRequest;

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
	public function __construct(){
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 * @return mixed
	 */
	public function handle(){
		$array = [
			'holy2' => ['abc'],
		];
		dd($array['holy'] ?? ['Not holy']);
	}
}