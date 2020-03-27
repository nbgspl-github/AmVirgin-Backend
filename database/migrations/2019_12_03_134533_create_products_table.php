<?php

use App\Constants\Constants;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration{
	/**
	 * Run the migrations.
	 * @return void
	 */
	public function up(){
		Schema::create('products', function (Blueprint $table){
			$table->bigIncrements('id');
			$table->string('name', 500)->comment('Name or title of product.')->nullable();
			$table->string('slug', 1000)->comment('Slug title of product.');
			$table->unsignedBigInteger('categoryId')->comment('Category under which this product falls.');
			$table->unsignedBigInteger('sellerId')->comment('Seller to whom this product belongs.');
			$table->unsignedSmallInteger('brandId')->comment('Brand to which this product belongs.');
			$table->enum('listingStatus', ['active', 'inactive'])->comment('Whether the product will show up in catalog listing or not?');
			$table->integer('originalPrice')->default(0)->comment('MRP of product.');
			$table->integer('sellingPrice')->default(0)->comment('Actual selling price of product');
			$table->string('fulfillmentBy')->comment('How the order is fulfilled...by seller or through external courier service?');
			$table->string('hsn')->comment('Harmonized System of Nomenclature code as defined by rules');
			$table->string('currency')->default('INR')->comment('Currency to be shown alongside product price.');
			$table->integer('taxRate')->comment('Will always be in percentage and will add up.');
			$table->boolean('promoted')->default(false)->comment('Whether is product is promoted or not?');
			$table->timestamp('promotionStart')->nullable()->comment('Start timestamp, valid if product is promoted.');
			$table->timestamp('promotionEnd')->nullable()->comment('End timestamp, valid if product is promoted.');
			$table->float('rating', 4, 2)->default(0.0)->comment('Rating of product.');
			$table->bigInteger('hits')->default(0)->comment('Number of times this product was viewed by customers.');
			$table->bigInteger('stock')->default(0)->comment('How many units are in stock?');
			$table->boolean('draft')->default(false)->comment('True if the seller is midway creating this product.');
			$table->string('shortDescription', 1000)->comment('Short description for this product.');
			$table->text('longDescription')->comment('Long description for this product, supports HTML formatting.');
			$table->string('sku')->comment('Stock Keeping Unit identifier, unique for each product');
			$table->string('styleCode')->nullable()->comment('For variants of the same product, this will be same. Unique code which sets up relations between different variants of the same product.');
			$table->string('trailer', Constants::MaxFilePathLength)->nullable()->comment('Video path for product trailer.');
			$table->integer('procurementSla')->nullable()->comment('Number of days required before the seller can fulfill the order.');
			$table->integer('localShippingCost')->default(0)->comment('Shipping cost for local region.');
			$table->integer('zonalShippingCost')->default(0)->comment('Shipping cost for zone.');
			$table->integer('internationalShippingCost')->default(0)->comment('Cost for international shipping');
			$table->string('packageWeight')->nullable()->comment('Weight of final package in KG.');
			$table->string('packageLength')->nullable()->comment('Length of final package in CMS.');
			$table->string('packageBreadth')->nullable()->comment('Breadth of final package in CMS.');
			$table->string('packageHeight')->nullable()->comment('Height of final package in CMS.');
			$table->integer('domesticWarranty')->default(0)->comment('Applicable domestic warranty in months.');
			$table->integer('internationalWarranty')->default(0)->comment('Applicable international warranty in months.');
			$table->text('warrantySummary')->nullable()->comment('Brief description of warranty details.');
			$table->text('warrantyServiceType')->nullable()->comment('Type of warranty applicable on this product.');
			$table->text('coveredInWarranty')->nullable()->comment('What is covered in warranty?');
			$table->text('notCoveredInWarranty')->nullable()->comment('What does not come under warranty?');
			$table->string('primaryImage', Constants::MaxFilePathLength)->nullable()->comment('Primary or main image for the product');
			$table->softDeletes()->comment('Soft deleting in this context means the product is marked for deletion by seller.');
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down(){
		Schema::dropIfExists('products');
	}
}
