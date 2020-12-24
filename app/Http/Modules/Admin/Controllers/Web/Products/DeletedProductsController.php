<?php

namespace App\Http\Modules\Admin\Controllers\Web\Products;

use App\Http\Modules\Shared\Controllers\BaseController;
use App\Models\Product;

class DeletedProductsController extends BaseController
{
	public function index ()
	{
		$products = Product::where([
			['draft', false],
			['deleted', true],
		])->get();
		return view('admin.products.deleted.index')->with('products', $products);
	}
}