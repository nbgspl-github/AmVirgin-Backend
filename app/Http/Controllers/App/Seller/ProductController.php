<?php

namespace App\Http\Controllers\App\Seller;

use App\Http\Controllers\Base\ResourceController;
use App\Models\Product;

class ProductController extends ResourceController{

	/**
	 * ProductController constructor.
	 */
	public function __construct(){

	}

	/**
	 * @inheritDoc
	 */
	protected function provider(){
		return Product::class;
	}

	public function index(){

	}

	protected function resource(){

	}

	protected function collection(){

	}
}