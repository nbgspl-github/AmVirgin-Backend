<?php

namespace App\Console\Commands;

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
		$arr = [
			'product' => [
				'store' => [
					'success' => 'Reached success.',
				],
				'read' => [
					'success' => 'Reached read',
				],
			],
		];
		$string = 'product.store.success';
		$indices = explode('.', $string);
		$index = 0;
		$count = count($indices);
		$lastArray = $arr;
		$key = $indices[0];
		while ($index != $count) {
			$temp = $lastArray[$key];
			if (is_array($temp))
				$lastArray = $temp;
			else {
				$key = $temp;
				echo $key;
			}
			$index++;
		}
		var_dump($lastArray);
		return;
	}
}