<?php

namespace App\Console\Commands;

use App\Traits\GenerateUrls;
use FFMpeg\Format\Video\X264;
use Illuminate\Console\Command;
use ProtoneMedia\LaravelFFMpeg\Support\FFMpeg;

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
		$destination = 'public/videos/video_converted_playlist.m3u8';
		$lowBitrate = (new X264('aac'))->setKiloBitrate(250);
		$midBitrate = (new X264('aac'))->setKiloBitrate(500);
		$highBitrate = (new X264('aac'))->setKiloBitrate(1000);

		try {
			FFMpeg::open($source)
				->exportForHLS()
				->setSegmentLength(10) // optional
				->setKeyFrameInterval(48) // optional
				->addFormat($lowBitrate)
				->addFormat($midBitrate)
				->addFormat($highBitrate)
				->save($destination);
		} catch (\Throwable$exception) {
			dd($exception);
		}
	}

	public function handle ()
	{
		$prefix = 'AVG';
		$major = date('Ymd');
		$minor = date('His');
		$suffix = mt_rand(100, 999);
		echo("{$prefix}-{$major}-{$minor}-{$suffix}");
	}
}