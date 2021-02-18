<?php

use App\Library\Utils\Extensions\Arrays;
use App\Models\CatalogFilter;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCatalogFiltersTable extends Migration{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up(){
		Schema::create('catalog-filters', function (Blueprint $table){
			$table->bigIncrements('id');
			$table->string('label')->nullable()->comment('Label of filter section');
			$table->boolean('builtIn')->default(false)->comment('Is this one of the inbuilt filters?');
			$table->enum('builtInType', [Arrays::values(CatalogFilter::BuiltInFilters)])->nullable()->comment('If this is an inbuilt filter, what type is it?');
			$table->unsignedBigInteger('attributeId')->nullable()->comment('If this is a custom filter, what attribute does this target? For safety purposes, only attributes with predefined values are shown here.');
			$table->unsignedBigInteger('categoryId')->comment('What category does this filter belong to?');
			$table->boolean('allowMultiValue')->default(true)->comment('If this filter can take-in an array of values');
			$table->boolean('active')->default(true)->comment('Whether this filter is enabled and applicable?');
			$table->timestamps();

			if (appEnvironment(AppEnvironmentProduction)) {
				$table->foreign('attributeId')->references('id')->on(\App\Library\Enums\Common\Tables::Attributes)->onDelete('cascade');
				$table->foreign('categoryId')->references('id')->on(\App\Library\Enums\Common\Tables::Categories)->onDelete('cascade');
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
		Schema::dropIfExists('catalog-filters');
		Schema::enableForeignKeyConstraints();
	}
}
