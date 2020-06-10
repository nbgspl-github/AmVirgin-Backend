<?php

use App\Classes\Arrays;
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
			$table->string('description', 5000)->nullable()->comment('Appropriate description of what this attribute does (used for display purposes only)');
			$table->string('code', 100)->unique()->comment('A unique code to identify this attribute on the front-end.');
			$table->boolean('required')->default(false)->comment('Whether this attribute must be given one or more values when present in product creation form?');
			$table->boolean('useToCreateVariants')->default(false)->comment('Whether this attribute and its values be used to create product variants?');
			$table->boolean('useInLayeredNavigation')->default(false)->comment('Whether this attribute will appear as a filterable candidate in catalog listing?');
			$table->boolean('showInCatalogListing')->default(false)->comment('Whether this attribute and its values will be shown in catalog listing?');
			$table->boolean('combineMultipleValues')->default(false)->comment('Whether multiple values for this attribute should be treated as one single value?');
			$table->boolean('visibleToCustomers')->default(true)->comment('Whether this attribute and its value be visible to customer?');
			$table->boolean('predefined')->default(false)->comment('Whether this attribute has a predefined set of values.');
			$table->boolean('multiValue')->default(false)->comment('Whether this attribute allows multiple values to be entered.');
			$table->mediumInteger('minValues')->default(0)->comment('Minimum required values for this attribute in multi-value mode.');
			$table->mediumInteger('maxValues')->default(0)->comment('Maximum number of values allowed for this attribute');
			$table->enum('interface', Arrays::values(Attribute::Interface))->default(Attribute::Interface['TextLabel'])->comment('What type of interface will be shown to user to select value for this attribute?');
			$table->json('values')->comment('Values for this attribute');
			$table->timestamps();
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