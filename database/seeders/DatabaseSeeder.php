<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class DatabaseSeeder extends Seeder
{
	/**
	 * Seed the application's database.
	 *
	 * @return void
	 */
	public function run ()
	{
		Schema::disableForeignKeyConstraints();
		$this->call(CountrySeeder::class);
		$this->call(StateSeeder::class);
		$this->call(AdminSeeder::class);
		$this->call(CustomerSeeder::class);
		$this->call(SellerSeeder::class);
		$this->call(PrimitiveTypeSeeder::class);
		$this->call(CategorySeeder::class);
		Schema::enableForeignKeyConstraints();
	}
}