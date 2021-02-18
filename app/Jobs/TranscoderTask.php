<?php

namespace App\Jobs;

use App\Library\Utils\Extensions\Time;
use App\Library\Utils\Uploads;
use App\Models\Video\Source;
use FFMpeg\Format\Video\X264;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use ProtoneMedia\LaravelFFMpeg\Exporters\HLSExporter;
use ProtoneMedia\LaravelFFMpeg\Support\FFMpeg;

class TranscoderTask implements ShouldQueue
{
	use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

	protected ?Source $source;

	protected ?string $path;

	protected string $hlsExtension = 'm3u8';

	public $timeout = 86400;

	protected $low, $mid, $high, $ultra;

	protected $activityLog;

	/**
	 * Create a new job instance.
	 *
	 * @param Source $source
	 */
	public function __construct (Source $source)
	{
		$this->source = $source;
		$this->path = $this->source->getRawOriginal('file');
		$this->low = (new X264('aac', 'libx264'))->setKiloBitrate(250);
		$this->mid = (new X264('aac', 'libx264'))->setKiloBitrate(500);
		$this->high = (new X264('aac', 'libx264'))->setKiloBitrate(1000);
		$this->ultra = (new X264('aac', 'libx264'))->setKiloBitrate(1500);
		$this->emit('Queued');
	}

	public function handle () : void
	{
		$this->emit('Encoding');
		$encoder = $this->loadMediaIntoEncoder();
		$encoder = $this->addRequiredFormats($encoder);
		$this->export($encoder);
		$this->emit('Completed');
	}

	protected function loadMediaIntoEncoder () : HLSExporter
	{
		return FFMpeg::fromDisk('secured')
			->open($this->path)
			->exportForHLS()
			->onProgress(fn ($progress) => $this->updateProgress($progress));
	}

	protected function export (HLSExporter $encoder)
	{
		$directory = $this->makeContainerDirectory();
		$exportPath = $this->exportPath($directory);
		$encoder->toDisk('secured')->save($exportPath);
		$this->source->update(['file' => $exportPath]);
	}

	protected function makeContainerDirectory () : string
	{
		$filename = pathinfo(basename($this->path), PATHINFO_FILENAME);
		if (is_array($filename)) {
			$filename = "{$filename[0]}";
		};
		if (!Uploads::access()->exists("videos/streams/{$filename}"))
			Uploads::access()->makeDirectory("videos/streams/{$filename}");
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

	protected function addRequiredFormats (HLSExporter $encoder) : HLSExporter
	{
		$encoder->addFormat($this->low, function ($media) {
			$media->scale(640, 480);
		});
		$encoder->addFormat($this->mid, function ($media) {
			$media->scale(1280, 720);
		});
		$encoder->addFormat($this->high, function ($media) {
			$media->scale(1920, 1080);
		});
		$encoder->addFormat($this->ultra, function ($media) {
			$media->scale(3840, 2160);
		});
		return $encoder;
	}

	protected function emit (string $event)
	{
		switch ($event) {
			case 'Queued':
				$this->activityLog = $this->source->queues()->create(['video_id' => $this->source->video_id, 'status' => 'Queued']);
				break;

			case 'Encoding':
				$this->activityLog->update(['started_at' => now()->format(Time::MYSQL_FORMAT), 'status' => 'Encoding']);
				break;

			case 'Completed':
				$this->activityLog->update(['completed_at' => now()->format(Time::MYSQL_FORMAT), 'status' => 'Completed']);
				break;
		}
	}

	protected function updateProgress ($progress)
	{
		$this->activityLog->update(['progress' => $progress]);
	}
}