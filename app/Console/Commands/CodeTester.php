<?php

namespace App\Console\Commands;

use App\Models\MediaServer;
use App\Traits\GenerateUrls;
use Exception;
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
		try {
			$server = MediaServer::retrieveThrows(55);
		}
		catch (Exception $exception) {
			echo $exception->getMessage();
		}
		return;
	}
}