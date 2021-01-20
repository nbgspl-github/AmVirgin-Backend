<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class TranscoderTask implements \Illuminate\Contracts\Queue\ShouldQueue
{
	use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

	protected ?\App\Models\Video\Source $source;

	protected ?string $path;

	protected string $hlsExtension = 'm3u8';

	public $timeout = 3000;

	protected $low, $mid, $high, $ultra;

	protected $encodingQueue;

	/**
	 * Create a new job instance.
	 *
	 * @param \App\Models\Video\Source $source
	 */
	public function __construct (\App\Models\Video\Source $source)
	{
		$this->source = $source;
		$this->path = $this->source->getRawOriginal('file');
		$this->low = (new \FFMpeg\Format\Video\X264('aac', 'libx264'))->setKiloBitrate(250);
		$this->mid = (new \FFMpeg\Format\Video\X264('aac', 'libx264'))->setKiloBitrate(500);
		$this->high = (new \FFMpeg\Format\Video\X264('aac', 'libx264'))->setKiloBitrate(1000);
		$this->ultra = (new \FFMpeg\Format\Video\X264('aac', 'libx264'))->setKiloBitrate(1500);
		$this->encodingQueue = $source->queues()->create(['video_id' => $source->video_id, 'status' => 'Queued']);
	}

	public function handle () : void
	{
		$this->encodingQueue->update(['started_at' => now()->format(\App\Library\Utils\Extensions\Time::MYSQL_FORMAT), 'status' => 'Encoding']);
		$transcoder = \ProtoneMedia\LaravelFFMpeg\Support\FFMpeg::fromDisk('secured')->open($this->path)->exportForHLS();
		$transcoder = $transcoder->inFormat(new \ProtoneMedia\LaravelFFMpeg\FFMpeg\CopyFormat());
		$transcoder->onProgress(fn ($progress) => $this->updateProgress($progress));
		$transcoder = $this->addFormats($transcoder);
		$directory = $this->makeHlsContainerDirectory();
		$exportPath = $this->exportPath($directory);
		$transcoder->toDisk('secured')->save($exportPath);
		$this->source->update(['file' => $exportPath]);
		$this->encodingQueue->update(['completed_at' => now()->format(\App\Library\Utils\Extensions\Time::MYSQL_FORMAT), 'status' => 'Completed']);
	}

	protected function updateProgress ($progress)
	{
		$this->encodingQueue->update(['progress' => $progress]);
	}

	protected function makeHlsContainerDirectory () : string
	{
		$filename = pathinfo(basename($this->path), PATHINFO_FILENAME);
		if (is_array($filename)) {
			$filename = "{$filename[0]}";
		};
		if (!\App\Library\Utils\Uploads::access()->exists("videos/streams/{$filename}"))
			\App\Library\Utils\Uploads::access()->makeDirectory("videos/streams/{$filename}");
		return "videos/streams/{$filename}";
	}

	protected function exportPath (string $directory) : string
	{
		$filename = pathinfo(basename($this->path), PATHINFO_FILENAME);
		if (is_array($filename)) {
			return "{$filename[0]}.{$this->hlsExtension}";
		}
		return "{$directory}/{$this->source->video_id}_{$this->source->id}.{$this->hlsExtension}";
	}

	protected function addFormats (\ProtoneMedia\LaravelFFMpeg\Exporters\HLSExporter $transcoder) : \ProtoneMedia\LaravelFFMpeg\Exporters\HLSExporter
	{
		$transcoder->addFormat($this->low, function ($media) {
			$media->scale(640, 480);
		});
		$transcoder->addFormat($this->mid, function ($media) {
			$media->scale(1280, 720);
		});
//		$transcoder->addFormat($this->high, function ($media) {
//			$media->scale(1920, 1080);
//		});
//		$transcoder->addFormat($this->high, function ($media) {
//			$media->scale(3840, 2160);
//		});
		return $transcoder;
	}
}