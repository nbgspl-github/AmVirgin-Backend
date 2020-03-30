<?php

use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run(){
		\App\Models\Auth\Customer::createOrUpdate(['email' => 'customer@amvirgin.com'], [
			'name' => 'Customer',
			'email' => 'customer@amvirgin.com',
			'password' => '$2y$10$9.1vQbzkT.yfk3e5ttvkTOJ.U9HKlaez8mJzknnq1lm2uu0QCx7uy',
			'mobile' => mt_rand(6000000000, 9000000000),
			'email_verified_at' => \App\Classes\Time::mysqlStamp(),
			'otp' => 1234,
		]);
	}
}
