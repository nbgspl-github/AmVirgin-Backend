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
			'name' => 'String (plain or formatted text)',
			'primitiveType' => 'string',
			'usableFunction' => 'strval',
			'measurable' => false,
		]);
		\App\Models\PrimitiveType::create([
			'typeCode' => 'int',
			'name' => 'Integer (non-decimal)',
			'primitiveType' => 'int',
			'usableFunction' => 'intval',
			'measurable' => true,
		]);
		\App\Models\PrimitiveType::create([
			'typeCode' => 'float',
			'name' => 'Float (decimal)',
			'primitiveType' => 'float',
			'usableFunction' => 'floatval',
			'measurable' => true,
		]);
		\App\Models\PrimitiveType::create([
			'typeCode' => 'bool',
			'name' => 'Boolean (binary choice)',
			'primitiveType' => 'bool',
			'usableFunction' => 'boolval',
			'measurable' => false,
		]);
		\App\Models\PrimitiveType::create([
			'typeCode' => 'color',
			'name' => 'Color (name of color)',
			'primitiveType' => 'string',
			'usableFunction' => 'strval',
			'measurable' => false,
		]);
	}
}