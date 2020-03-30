<?php

use Illuminate\Database\Seeder;

class SellerSeeder extends Seeder{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run(){
		\App\Models\Auth\Seller::createOrUpdate(['email' => 'seller@amvirgin.com'], [
			'name' => 'Seller',
			'email' => 'seller@amvirgin.com',
			'password' => '$2y$12$aK6f8nhxWSl6rNxX/CdJ..94rfFTq9PdcPmQZdKJM/1WSDwqWiw8G',
			'mobile' => mt_rand(6000000000, 9000000000),
			'email_verified_at' => \App\Classes\Time::mysqlStamp(),
			'otp' => 1234,
			'businessName' => 'AmVirgin Entertainment Pvt. Ltd.',
			'description' => 'We are an exclusive store for all kinds of AmVirgin merchandise.',
			'',
		]);
	}
}
