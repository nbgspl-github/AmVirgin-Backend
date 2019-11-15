<?php

namespace App\Console\Commands;

use App\Models\Auth\Admin;
use App\Models\Auth\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class TestRun extends Command {
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'test:run';

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
		$admin = new User();
		$admin->name = "Aviral Singh";
		$admin->email = "avx@gmail.com";
		$admin->password = Hash::make("123456789");
		$admin->save();
//		echo "Token is => " . $admin->createToken(env('APP_NAME'))->accessToken;
		return;
	}
}
