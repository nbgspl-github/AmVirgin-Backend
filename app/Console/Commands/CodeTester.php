<?php

namespace App\Console\Commands;

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
		$destination = 'public/videos/video_converted.mp4';
		$lowBitrate = (new X264('aac'))->setKiloBitrate(250);
		$midBitrate = (new X264('aac'))->setKiloBitrate(500);
		$highBitrate = (new X264('aac'))->setKiloBitrate(1000);
	}

	public function handle ()
	{
		$lowBitrate = (new X264('aac'))->setKiloBitrate(250);
		$midBitrate = (new X264('aac'))->setKiloBitrate(500);
		$highBitrate = (new X264('aac'))->setKiloBitrate(1000);
		$progress = $this->output->createProgressBar(100);
		$this->info('Beginning encoding...');
		FFMpeg::fromDisk('secured')
			->open('extra/dbz.mp4')
			->exportForHLS()
			->setSegmentLength(10)
			->setKeyFrameInterval(48)
			->addFormat($lowBitrate)
			->addFormat($midBitrate)
			->addFormat($highBitrate)
			->onProgress(function ($percentage) use (&$progress) {
				$progress->setProgress($percentage);
			})
			->save('dbz_adaptive.m3u8');
	}
}