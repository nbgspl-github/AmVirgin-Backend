<?php

namespace App\Console\Commands;

use FFMpeg\Format\Video\X264;
use Illuminate\Console\Command;

class CodeTester extends Command
{
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
	public function __construct ()
	{
		parent::__construct();
	}

	public function handleX () : void
	{
		$source = 'public/videos/video.mp4';
		$destination = 'public/videos/video_converted.mp4';
		$lowBitrate = (new X264('aac'))->setKiloBitrate(250);
		$midBitrate = (new X264('aac'))->setKiloBitrate(500);
		$highBitrate = (new X264('aac'))->setKiloBitrate(1000);
	}

	public function handle ()
	{
		/**
		 * @var $customer \App\Models\Auth\Customer
		 */
		$customer = \App\Models\Auth\Customer::query()->find(18);
		$customer->sendPasswordResetAcknowledgement(\App\Library\Utils\Extensions\Str::random(80), 'email');
	}
}