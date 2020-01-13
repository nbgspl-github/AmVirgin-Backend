<?php

namespace App\Http\Controllers\Web\Admin\Products;

use App\Http\Controllers\BaseController;
use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;

class ProductsBase extends BaseController{
	protected function list(){
		return Product::all();
	}

	protected function get($id){
		return Product::find($id);
	}

	protected function getThrows($id){
		return Product::retrieveThrows($id);
	}

	protected function query(){
		return Product::class;
	}

	protected function softDeleted(): Builder{
		return $this->query()::where('deleted', true);
	}
}