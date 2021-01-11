<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class TranscoderTask
{
	use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

	protected ?\App\Models\Video\Source $source;

	protected array $formats;

	protected ?string $path;

	protected string $hlsExtension = 'm3u8';

	/**
	 * Create a new job instance.
	 *
	 * @param \App\Models\Video\Source $source
	 */
	public function __construct (\App\Models\Video\Source $source)
	{
		$this->source = $source;
		$this->path = $this->source->getRawOriginal('file');
		$this->formats = [
			\App\Library\Enums\Videos\Quality::SD => (new \FFMpeg\Format\Video\X264('aac'))->setKiloBitrate(1200),
//			\App\Library\Enums\Videos\Quality::HD => (new \FFMpeg\Format\Video\X264('aac'))->setKiloBitrate(2500),
//			\App\Library\Enums\Videos\Quality::FHD => (new \FFMpeg\Format\Video\X264('aac'))->setKiloBitrate(5000),
		];
	}

	public function handle () : void
	{
		$transcoder = \ProtoneMedia\LaravelFFMpeg\Support\FFMpeg::fromDisk('secured')->open($this->path)->exportForHLS();
		\App\Library\Utils\Extensions\Arrays::eachAssociative($this->formats,
			function ($bitrate, \FFMpeg\Format\Video\DefaultVideo $format) use (&$transcoder) {
				$transcoder->addFormat($format);
			}
		);
		$transcoder->save($this->exportPath());
		$this->source->update(['file' => $this->exportPath()]);
	}

	protected function exportPath () : string
	{
		$filename = pathinfo(basename($this->path), PATHINFO_FILENAME);
		if (is_array($filename)) {
			return "{$filename[0]}.{$this->hlsExtension}";
		}
		return "{$filename}.{$this->hlsExtension}";
	}
}