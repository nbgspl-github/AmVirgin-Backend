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

	public function handle ()
	{
		$source = \App\Models\Video\Source::query()->find(1);
		try {
			\App\Jobs\TranscoderTask::dispatch($source)->onQueue('default');
		} catch (\Throwable $exception) {
			dd($exception);
		}
	}
}