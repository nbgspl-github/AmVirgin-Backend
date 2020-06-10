<?php

use App\Classes\Time;
use App\Models\Auth\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run(){
		Admin::truncate();
		Admin::updateOrCreate(['email' => 'super.admin@amvirgin.com'], [
			'name' => 'Super Admin',
			'email' => 'super.admin@amvirgin.com',
			'password' => '1234567890',
			'mobile' => mt_rand(6000000000, 9000000000),
			'email_verified_at' => Time::mysqlStamp(),
			'super' => true,
		]);
	}
}
