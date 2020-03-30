<?php

use Illuminate\Database\Seeder;

class PrimitiveTypeSeeder extends Seeder{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run(){
		\App\Models\PrimitiveType::createOrUpdate(['typeCode' => 'string'], [
			'typeCode' => 'string',
			'name' => 'String (plain or formatted text) (Name, URL, Address, IP etc)',
			'primitiveType' => 'string',
			'usableFunction' => 'strval',
			'measurable' => false,
		]);
		\App\Models\PrimitiveType::createOrUpdate(['typeCode' => 'int'], [
			'typeCode' => 'int',
			'name' => 'Integer (non-decimal) (Any non-decimal value like 1, 2, 3, 4 confined by the upper and lower limit, if applicable)',
			'primitiveType' => 'int',
			'usableFunction' => 'intval',
			'measurable' => true,
		]);
		\App\Models\PrimitiveType::createOrUpdate(['typeCode' => 'float'], [
			'typeCode' => 'float',
			'name' => 'Float (decimal) (Any decimal value like 22.55, 3.4, 1.2 confined by the upper and lower limit, if applicable)',
			'primitiveType' => 'float',
			'usableFunction' => 'floatval',
			'measurable' => true,
		]);
		\App\Models\PrimitiveType::createOrUpdate(['typeCode' => 'bool'], [
			'typeCode' => 'bool',
			'name' => 'Boolean (binary choice) (Yes or No)',
			'primitiveType' => 'bool',
			'usableFunction' => 'boolval',
			'measurable' => false,
		]);
		\App\Models\PrimitiveType::createOrUpdate(['typeCode' => 'color'], [
			'typeCode' => 'color',
			'name' => 'Color (name of any standard color) (Black, Yellow, Red, White etc)',
			'primitiveType' => 'string',
			'usableFunction' => 'strval',
			'measurable' => false,
		]);
	}
}