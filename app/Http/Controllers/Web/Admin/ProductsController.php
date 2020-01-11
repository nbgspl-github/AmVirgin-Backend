<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\BaseController;
use App\Models\Product;

class ProductsController extends BaseController{
	public function __construct(){
		parent::__construct();
	}

	public function index(){
		$products = Product::all();
		return view('admin.products.index')->with('products', $products);
	}
}