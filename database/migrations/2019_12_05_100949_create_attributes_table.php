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
			$table->unsignedBigInteger('categoryId')->comment('The category this attribute belongs to');
			$table->string('name')->comment('Name of this attribute (used for display purposes only');
			$table->string('description', 5000)->comment('Appropriate description of what this attribute does (used for display purposes only)');
			$table->string('code', 100)->unique()->comment('A unique code to identify this attribute on the front-end.');
			$table->enum('sellerInterfaceType', [Attribute::SellerInterfaceType['DropDown'], Attribute::SellerInterfaceType['Input'], Attribute::SellerInterfaceType['Text'], Attribute::SellerInterfaceType['Radio']])->comment('What kind of UI should be shown to seller when taking this attributes\' values?');
			$table->enum('customerInterfaceType', [Attribute::CustomerInterfaceType['Options'], Attribute::CustomerInterfaceType['Readable']])->comment('What kind of UI should be shown to the customer?')->nullable();
			$table->string('primitiveType', 10)->comment('What type of values does this attribute holds?');
			$table->boolean('required')->default(false)->comment('Whether this attribute must be given one or more values when present in product creation form?');
			$table->boolean('filterable')->default(false)->comment('States whether filtering can be applied to this attribute.');
			$table->boolean('productNameSegment')->default(false)->comment('Whether this attribute\'s value(s) will be used to form the product name.');
			$table->integer('segmentPriority')->default(Attribute::SegmentPriority['Overlook'])->comment('Defines a number used to determine where in the product name, the value(s) of this attribute will appear. Ignored if 0, valid from 1 through 10.');
			$table->boolean('bounded')->default(false)->comment('Whether to set a limit on the upper and lower value of this type.');
			$table->boolean('multiValue')->default(false)->comment('Whether this attribute allows multiple values to be entered.');
			$table->mediumInteger('maxValues')->default(0)->comment('How many values does this attribute allows entering in multi-value mode?');
			$table->string('minimum')->nullable()->comment('Lower limit, ignored if bounded is false');
			$table->string('maximum')->nullable('Upper limit, ignored if bounded is false');
			$table->timestamps();

			if (appEnvironment(AppEnvironmentProduction)) {
				$table->foreign('categoryId')->references('id')->on(\App\Interfaces\Tables::Categories)->onDelete('cascade');
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