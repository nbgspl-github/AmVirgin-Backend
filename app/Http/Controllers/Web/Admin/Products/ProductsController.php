<?php

namespace App\Http\Controllers\Web\Admin\Products;

use App\Queries\VideoQuery;

class ProductsController extends ProductsBase{
	public function __construct(){
		parent::__construct();
	}

	public function index(){
		$products = $this->list();
		return view('admin.products.index')->with('products', $products);
	}
}