<?php

namespace App\Http\Controllers\Web\Admin\Products;

class DeletedProductsController extends ProductsController{
	public function index(){
		$products = $this->softDeleted()->get();
		return view('admin.products.deleted.index')->with('products', $products);
	}
}