<?php

namespace App\Console\Commands;

use App\Classes\Builders\Notifications\PushNotification;
use App\Classes\Methods;
use App\Http\Controllers\Web\CategoriesController;
use App\Models\Auth\Admin;
use App\Models\Customer;
use App\Models\Seller;
use App\Traits\GenerateUrls;
use Illuminate\Console\Command;

class CodeTester extends Command{
	use GenerateUrls;

	protected $urlMethodPrefix = 'myUrl';

	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'code:test';

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
	 *
	 * @return mixed
	 */
	public function handle(){
		$results = Seller::where(function ($query){
			$query->where('mobile', '6306520374')->orWhere('email', 'seller@gmail.comx');
		})->get();
		echo sprintf("Count of results is %d.", $results->count());
		return;
	}
}