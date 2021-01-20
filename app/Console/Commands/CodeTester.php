<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CodeTester extends Command
{
	use \App\Traits\NotifiableViaSms;

	const TwoHours = 7200;

	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'c:t';

	protected $mobile = "8375976617";

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
		$s = \App\Models\Video\Source::find(13);
		\App\Jobs\TranscoderTask::dispatchNow($s);
	}
}