<?php

namespace App\Console\Commands;

use App\Traits\GenerateUrls;
use Illuminate\Console\Command;

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
		$pickup = jsonEncode([
			'date' => '06-01-2020',
			'time' => '05:02 PM',
		]);
		$dateX = jsonDecodeArray($pickup);
		$date = $dateX['date'];
		$time = $dateX['time'];
		$timestamp = strtotime(sprintf("%s %s", $date, $time));
		$current = time();
		$difference = abs($timestamp - $current);
		dd($difference);
		if ($difference <= self::TwoHours)
			echo "Yes";
		else
			echo 'Nope';
		return;
	}
}