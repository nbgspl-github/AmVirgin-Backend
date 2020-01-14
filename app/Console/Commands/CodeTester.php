<?php

namespace App\Console\Commands;

use App\Traits\GenerateUrls;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class CodeTester extends Command{
	use GenerateUrls;
	const TwoHours = 7200;

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
		$file = Storage::disk('secured')->url('secured/videos/0G5WaTnK6lbQh1JmBw0RcI0Rxk7vmBnnJSKmEyvF.mp4');
		echo $file;
		return;
	}
}