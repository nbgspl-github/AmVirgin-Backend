<?php

namespace App\Console\Commands;

use App\Models\Genre;
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
		$model = new Genre();
		echo $model->downloadPoster();
		return;
	}
}