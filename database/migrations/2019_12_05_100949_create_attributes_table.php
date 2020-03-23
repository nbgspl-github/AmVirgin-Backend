<?php

use App\Constants\SellerAttributeInterfaceType;
use App\Constants\CustomerAttributeInterfaceType;
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
			$table->integer('categoryId')->comment('The category this attribute belongs to');
			$table->string('name')->comment('Name or key of this attribute');
			$table->enum('sellerInterfaceType', [SellerAttributeInterfaceType::DropDown, SellerAttributeInterfaceType::InputField, SellerAttributeInterfaceType::MultiEntry, SellerAttributeInterfaceType::SingleChoice])->comment('What kind of UI should be shown to seller when taking this attributes\' values?');
			$table->enum('customerInterfaceType', [CustomerAttributeInterfaceType::Popover, CustomerAttributeInterfaceType::InLine])->comment('What kind of UI should be shown to the customer?');
			$table->string('genericType')->nullable()->comment('What type of values does this attribute holds?');
			$table->boolean('required')->default(false)->comment('Whether this attribute must be given one or more values when present in product creation form?');
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down(){
		Schema::dropIfExists('attributes');
	}
}