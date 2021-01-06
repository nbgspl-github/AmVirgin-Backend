<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class FillVideoMetadata implements ShouldQueue
{
	use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

	protected ?\App\Models\Video\Source $source;

	/**
	 * Create a new job instance.
	 *
	 * @param \App\Models\Video\Source $source
	 */
	public function __construct (\App\Models\Video\Source $source)
	{
		$this->source = $source;
	}

	/**
	 * Execute the job.
	 *
	 * @return void
	 */
	public function handle ()
	{
		$media = \ProtoneMedia\LaravelFFMpeg\Support\FFMpeg::fromDisk('secured')->open($this->source->getRawOriginal('file'));
		$seconds = $media->getDurationInSeconds();
		$this->source->update([
			'duration' => \App\Library\Utils\Extensions\Time::toDuration($seconds, "%02d:%02d:%02d")
		]);
	}
}