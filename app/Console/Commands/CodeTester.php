<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CodeTester extends Command
{
	const TwoHours = 7200;

	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'c:t';

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
		try {
			$source = \App\Models\Video\Source::query()->find(14);
			\App\Jobs\TranscoderTask::dispatchNow($source);
		} catch (\Throwable $exception) {
			dd($exception);
		}
	}
}