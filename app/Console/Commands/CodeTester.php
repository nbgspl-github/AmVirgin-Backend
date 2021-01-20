<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CodeTester extends Command
{
	use \App\Traits\NotifiableViaSms;

	const TwoHours = 7200;

	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'c:t';

	protected $mobile = "8375976617";

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
	public function __construct ()
	{
		parent::__construct();
	}

	public function handle ()
	{
		$directory = "app/public/uploads/0WNjses4ICBQerxef0wKw9wv";
		$directoryPlain = "uploads/0WNjses4ICBQerxef0wKw9wv";
		$base = fopen(storage_path("{$directory}/empty.ext"), 'ab');
		$target = "{$directory}/video.mp4";
		for ($i = 1; ; $i++) {
			$file = "{$directory}/{$i}.ext";
			if (!file_exists(storage_path($file))) {
				echo "File {$file} not found\n";
				break;
			}
//			echo "Appending {$file} to {$base}\n";
			$resource = fopen(storage_path($file), 'rb');
			$buffer = fread($resource, filesize(storage_path($file)));
			fwrite($base, $buffer);
		}
		fclose($base);
		copy(storage_path("{$directory}/empty.ext"), storage_path("{$directory}/video.mp4"));
//		\App\Library\Utils\Uploads::access()->copy($base, $target);
	}
}