<?php

namespace App\Console\Commands;

use FFMpeg\Format\Video\X264;
use Illuminate\Console\Command;
use ProtoneMedia\LaravelFFMpeg\Exporters\EncodingException;
use ProtoneMedia\LaravelFFMpeg\Support\FFMpeg;

class EncoderTask extends Command
{
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'v:e';

	protected $path = "app\public\movies\movie.mp4";

	protected $low, $mid, $high, $ultra;

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
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return int
	 */
	public function handle()
	{
		try {
			$this->low = (new X264('aac', 'libx264'))->setKiloBitrate(250);
			$this->mid = (new X264('aac', 'libx264'))->setKiloBitrate(500);
			$this->high = (new X264('aac', 'libx264'))->setKiloBitrate(1000);
			$this->ultra = (new X264('aac', 'libx264'))->setKiloBitrate(1500);
			$encoder = FFMpeg::fromDisk('secured')
				->open($this->path)
				->exportForHLS()
				->addFormat($this->low)
				->addFormat($this->mid)
				->addFormat($this->high)
				->addFormat($this->ultra)
				->onProgress(fn($progress) => $this->updateProgress($progress))
				->toDisk('secured')->save('app/public/movies/encoded/video_adaptive.m3u8');
		} catch (\Throwable $e) {
			dd($e);
		}
	}

	public function updateProgress($value)
	{
		echo $value;
	}
}
