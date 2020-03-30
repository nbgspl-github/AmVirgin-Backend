<?php

use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run(){
		\App\Models\Auth\Admin::createOrUpdate(['email' => 'super.admin@amvirgin.com'], [
			'name' => 'Super Admin',
			'email' => 'super.admin@amvirgin.com',
			'password' => '$2y$12$aK6f8nhxWSl6rNxX/CdJ..94rfFTq9PdcPmQZdKJM/1WSDwqWiw8G',
			'mobile' => mt_rand(6000000000, 9000000000),
			'email_verified_at' => \App\Classes\Time::mysqlStamp(),
			'super' => true,
		]);
	}
}
