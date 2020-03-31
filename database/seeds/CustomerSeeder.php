<?php

use App\Classes\Time;
use App\Models\Auth\Customer;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run(){
		Customer::truncate();
		Customer::updateOrCreate(['email' => 'customer@amvirgin.com'], [
			'name' => 'Customer',
			'email' => 'customer@amvirgin.com',
			'password' => '12345678',
			'mobile' => mt_rand(6000000000, 9000000000),
			'email_verified_at' => Time::mysqlStamp(),
			'otp' => 1234,
		]);
	}
}
