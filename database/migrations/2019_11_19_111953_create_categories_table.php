<?php

use App\Constants\Constants;
use App\Models\Category;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoriesTable extends Migration{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up(){
		Schema::create('categories', function (Blueprint $table){
			$table->bigIncrements('id');
			$table->string('name')->comment('Name or title of category');
			$table->string('slug', 500)->comment('Slug title of category');
			$table->string('description', 2048)->nullable()->comment('Description for this category');
			$table->unsignedBigInteger('parentId')->comment('Parent of this child category, available only if this is a child category');
			$table->enum('type', [Category::Types['Root'], Category::Types['Category'], Category::Types['SubCategory'], Category::Types['Vertical']])->comment('Defines the type of category and inheritance level');
			$table->tinyInteger('order')->default(0)->comment('Defines a order in which this category would appear in listing');
			$table->string('icon', Constants::MaxFilePathLength)->nullable()->comment('An SVG or PNG file to be used as icon for this category in mobile apps');
			$table->enum('listingStatus', [Category::ListingStatus['Active'], Category::ListingStatus['Inactive']])->default(Category::ListingStatus['Active'])->comment('Whether this category will show up in listing');
			$table->integer('productCount')->default(0)->comment('What number of products fall under this category');
			$table->json('specials')->comment('Special attributes that may be temporary and do not fit anywhere else fall under this');
			$table->timestamps();

			if (appEnvironment(AppEnvironmentProduction)) {
				$table->foreign('parentId')->references('id')->on(\App\Interfaces\Tables::Categories)->onDelete('cascade');
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
		Schema::dropIfExists('categories');
		Schema::enableForeignKeyConstraints();
	}
}