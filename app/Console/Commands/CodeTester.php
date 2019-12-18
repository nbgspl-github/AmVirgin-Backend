<?php

namespace App\Console\Commands;

use App\Models\Customer;
use App\Traits\GenerateUrls;
use Illuminate\Console\Command;

class CodeTester extends Command{
	use GenerateUrls;

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
		$model = Customer::class;
		echo $model;
		return;
	}
}