<?php

namespace App\Console\Commands;

use App\Classes\Builders\Notifications\PushNotification;
use App\Http\Controllers\Web\CategoriesController;
use App\Models\Auth\Admin;
use Illuminate\Console\Command;

class CodeTester extends Command {
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
	public function __construct() {
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function handle() {
		return;
	}
}