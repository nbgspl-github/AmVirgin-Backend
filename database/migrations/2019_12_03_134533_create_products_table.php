<?php

use App\Constants\Constants;
use App\Interfaces\Tables;
use App\Models\Product;
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
			$table->unsignedBigInteger('brandId')->comment('Brand to which this product belongs.');
			$table->unsignedBigInteger('parentId')->nullable()->comment('Product to which this is a child, null if this is the parent');
			$table->enum('listingStatus', [Product::ListingStatus['Active'], Product::ListingStatus['Inactive']])->comment('Whether the product will show up in catalog listing or not?');
			$table->enum('type', [Product::Type['Simple'], Product::Type['Variant']])->comment('What type of product is this?');
			$table->enum('idealFor', [Product::IdealFor['Men'], Product::IdealFor['Women'], Product::IdealFor['Boys'], Product::IdealFor['Girls']])->comment('What gender would the product be most suitable for?');
			$table->float('originalPrice', 10, 2)->default(0)->comment('MRP of product.');
			$table->float('sellingPrice', 10, 2)->default(0)->comment('Actual selling price of product');
			$table->string('fulfillmentBy')->comment('How the order is fulfilled...by seller or through external courier service?');
			$table->string('hsn')->comment('Harmonized System of Nomenclature code as defined by rules');
			$table->string('currency')->default('INR')->comment('Currency to be shown alongside product price.');
			$table->integer('taxRate')->comment('Will always be in percentage and will add up.');
			$table->boolean('promoted')->default(false)->comment('Whether is product is promoted or not?');
			$table->timestamp('promotionStart')->nullable()->comment('Start timestamp, valid if product is promoted.');
			$table->timestamp('promotionEnd')->nullable()->comment('End timestamp, valid if product is promoted.');
			$table->float('rating', 4, 2)->default(0.0)->comment('Rating of product.');
			$table->bigInteger('hits')->default(0)->comment('Number of times this product was viewed by customers.');
			$table->integer('stock')->default(0)->comment('How many units are in stock?');
			$table->integer('lowStockThreshold')->default(0)->comment('When should a particular product be shown as low in stock to customer? (0 means disabled)');
			$table->boolean('draft')->default(false)->comment('True if the seller is midway creating this product.');
			$table->string('description', 2000)->comment('Short description for this product.');
			$table->string('sku')->comment('Stock Keeping Unit identifier, unique for each product');
			$table->string('styleCode')->nullable()->comment('For variants of the same product, this will be same. Unique code which sets up relations between different variants of the same product.');
			$table->string('trailer', Constants::MaxFilePathLength)->nullable()->comment('Video path for product trailer.');
			$table->tinyInteger('procurementSla')->nullable()->comment('Number of days required before the seller can fulfill the order.');
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
			$table->integer('maxQuantityPerOrder')->default(10)->comment('How many units of this product can be ordered in a single order');
			$table->boolean('approved')->default(false)->comment('Whether this product has been approved for selling by an admin');
			$table->unsignedBigInteger('approvedBy')->nullable()->comment('Id of admin who has approved this product');
			$table->timestamp('approvedAt')->nullable()->comment('When was this product approved by an admin');
			$table->string('primaryImage', Constants::MaxFilePathLength)->nullable()->comment('Primary or main image for the product');
			$table->json('specials')->nullable()->comment('Any special attributes that are temporary will come under this');
			$table->softDeletes('deletedAt')->comment('Soft deleting in this context means the product is marked for deletion by seller.');
			$table->timestamp('createdAt')->nullable();
			$table->timestamp('updatedAt')->nullable();

			if (appEnvironment(AppEnvironmentProduction)) {
				$table->foreign('categoryId')->references('id')->on(Tables::Categories)->onDelete('cascade');
				$table->foreign('sellerId')->references('id')->on(Tables::Sellers)->onDelete('cascade');
				$table->foreign('brandId')->references('id')->on(Tables::Brands)->onDelete('cascade');
				$table->foreign('parentId')->references('id')->on(Tables::Products)->onDelete('cascade');
				$table->foreign('hsn')->references('hsnCode')->on(Tables::HsnCodes)->onDelete('cascade');
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
		Schema::dropIfExists('products');
		Schema::enableForeignKeyConstraints();
	}
}
