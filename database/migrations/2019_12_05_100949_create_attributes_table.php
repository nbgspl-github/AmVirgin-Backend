<?php

use App\Models\Attribute;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAttributesTable extends Migration{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up(){
		Schema::create('attributes', function (Blueprint $table){
			$table->bigIncrements('id');
			$table->string('name')->comment('Name of this attribute (used for display purposes only');
			$table->string('slug', 500)->comment('Slug name of this attribute');
			$table->string('description', 5000)->nullable()->comment('Appropriate description of what this attribute does (used for display purposes only)');
			$table->string('code', 100)->unique()->comment('A unique code to identify this attribute on the front-end.');
			$table->enum('type', []);
			$table->boolean('predefined')->default(true)->comment('Whether this attribute has a predefined set of values.');
			$table->boolean('required')->default(false)->comment('Whether this attribute must be given one or more values when present in product creation form?');
			$table->boolean('multiValue')->default(false)->comment('Whether this attribute allows multiple values to be entered.');
			$table->mediumInteger('maxValues')->default(0)->comment('How many values does this attribute allows entering in multi-value mode?');
			$table->timestamps();

			if (appEnvironment(AppEnvironmentProduction)) {
				$table->foreign('primitiveType')->references('typeCode')->on(\App\Interfaces\Tables::PrimitiveTypes)->onDelete('cascade');
			}
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down(){
		Schema::disableForeignKeyConstraints();
		Schema::dropIfExists('attributes');
		Schema::enableForeignKeyConstraints();
	}
}