<?php

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
			$table->string('name', 500);
			$table->string('slug', 600);
			$table->unsignedBigInteger('categoryId');
			$table->unsignedBigInteger('sellerId');
			$table->string('productType')->nullable();
			$table->string('productMode')->nullable();
			$table->string('listingType')->nullable();
			$table->integer('originalPrice');
			$table->integer('offerValue')->comment('When type is fixed, this will be a percentage value, else fixed amount. Ignore if 0');
			$table->smallInteger('offerType')->comment('Either flat (fixed amount) or percentage');
			$table->string('currency')->default('INR');
			$table->float('taxRate')->comment('Will always be in percentage and will add up');
			$table->integer('countryId')->nullable();
			$table->integer('stateId')->nullable();
			$table->integer('cityId')->nullable();
			$table->string('zipCode', 10)->nullable();
			$table->string('address', 500)->nullable();
			$table->smallInteger('status')->default(1);
			$table->boolean('promoted')->default(false);
			$table->timestamp('promotionStart')->nullable();
			$table->timestamp('promotionEnd')->nullable();
			$table->boolean('visibility')->default(true);
			$table->float('rating', 4, 2)->default(0.0);
			$table->bigInteger('hits')->default(0);
			$table->bigInteger('stock')->default(0)->comment('How many units are in stock?');
			$table->string('shippingCostType')->nullable()->comment('Type of shipping cost - either free or chargeable');
			$table->integer('shippingCost')->default(0)->comment('If chargeable, how much will it be?');
			$table->boolean('soldOut')->default(false)->comment('If the product has gone out od stock this will be true');
			$table->boolean('deleted')->default(false)->comment('If the seller has deleted this product, this will be true');
			$table->boolean('draft')->default(false)->comment('If the seller is halfway done through putting details this will be true');
			$table->string('shortDescription', 1000);
			$table->string('longDescription', 5000);
			$table->string('sku');
			$table->string('trailer', 4096)->nullable();
			$table->boolean('hotDeal')->default(false)->after('trailer');
			$table->integer('sellingPrice')->default(false)->after('hotDeal');
			$table->string('hsn')->nullable()->after('hotDeal');
			$table->string('taxCode')->nullable()->after('hsn');
			$table->boolean('fulfilmentBy')->default(false)->after('taxCode');
			$table->string('procurementSla')->nullable()->after('fulfilmentBy');
			$table->integer('localShippingCost')->default(0)->after('procurementSla');
			$table->integer('zonalShippingCost')->default(0)->after('localShippingCost');
			$table->integer('internationalShippingCost')->default(0)->after('zonalShippingCost');
			$table->string('packageWeight')->nullable()->after('internationalShippingCost');
			$table->string('packageLength')->nullable()->after('packageWeight');
			$table->string('packageHeight')->nullable()->after('packageLength');
			$table->string('idealFor')->nullable()->after('packageHeight');
			$table->string('videoUrl')->nullable()->after('idealFor');
			$table->text('domesticWarranty')->nullable()->after('sellingPrice');
			$table->text('internationalWarranty')->nullable()->after('domesticWarranty');
			$table->text('warrantySummary')->nullable()->after('internationalWarranty');
			$table->text('warrantyServiceType')->nullable()->after('warrantySummary');
			$table->text('coveredInWarranty')->nullable()->after('warrantyServiceType');
			$table->text('notCoveredInWarranty')->nullable()->after('coveredInWarranty');
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
