<?php

use App\Classes\Time;
use App\Models\Auth\Seller;
use Illuminate\Database\Seeder;

class SellerSeeder extends Seeder{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run(){
		Seller::truncate();
		Seller::updateOrCreate(['email' => 'seller@amvirgin.com'], [
			'name' => 'Seller',
			'email' => 'seller@amvirgin.com',
			'password' => '12345678',
			'mobile' => mt_rand(6000000000, 9000000000),
			'email_verified_at' => Time::mysqlStamp(),
			'otp' => 1234,
			'businessName' => 'AmVirgin Entertainment Pvt. Ltd.',
			'description' => 'We are an exclusive store for all kinds of AmVirgin merchandise.',
			'countryId' => 101,
			'stateId' => 38,
			'cityId' => 5022,
			'pinCode' => 201301,
			'address' => 'B-11, Block B, Sector 65, Noida 201301',
		]);
	}
}
