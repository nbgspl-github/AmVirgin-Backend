<?php

namespace App\Http\Modules\Admin\Controllers\Web\Products\Attributes;

class DetailController extends \App\Http\Modules\Admin\Controllers\Web\WebController
{
	public function __construct ()
	{
		parent::__construct();
		$this->middleware(AUTH_ADMIN);
	}

	public function pending (\App\Models\Product $product)
	{
		return view('admin.products.details.pending')->with('product', $product);
	}
}