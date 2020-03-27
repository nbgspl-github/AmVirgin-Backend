<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder{
	/**
	 * Seed the application's database.
	 *
	 * @return void
	 */
	public function run(){
		\App\Models\PrimitiveType::create([
			'typeCode' => 'string',
			'name' => 'String (plain or formatted text) (Name, URL, Address, IP etc)',
			'primitiveType' => 'string',
			'usableFunction' => 'strval',
			'measurable' => false,
		]);
		\App\Models\PrimitiveType::create([
			'typeCode' => 'int',
			'name' => 'Integer (non-decimal) (Any non-decimal value like 1, 2, 3, 4 confined by the upper and lower limit, if applicable)',
			'primitiveType' => 'int',
			'usableFunction' => 'intval',
			'measurable' => true,
		]);
		\App\Models\PrimitiveType::create([
			'typeCode' => 'float',
			'name' => 'Float (decimal) (Any decimal value like 22.55, 3.4, 1.2 confined by the upper and lower limit, if applicable)',
			'primitiveType' => 'float',
			'usableFunction' => 'floatval',
			'measurable' => true,
		]);
		\App\Models\PrimitiveType::create([
			'typeCode' => 'bool',
			'name' => 'Boolean (binary choice) (Yes or No)',
			'primitiveType' => 'bool',
			'usableFunction' => 'boolval',
			'measurable' => false,
		]);
		\App\Models\PrimitiveType::create([
			'typeCode' => 'color',
			'name' => 'Color (name of any standard color) (Black, Yellow, Red, White etc)',
			'primitiveType' => 'string',
			'usableFunction' => 'strval',
			'measurable' => false,
		]);
	}
}