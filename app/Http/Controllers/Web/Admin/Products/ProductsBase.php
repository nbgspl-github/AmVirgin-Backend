<?php

namespace App\Http\Controllers\Web\Admin\Products;

use App\Http\Modules\Shared\Controllers\BaseController;
use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;

class ProductsBase extends BaseController
{
	protected function list ()
	{
		return Product::all();
	}

	protected function get ($id)
	{
		return Product::find($id);
	}

	protected function getThrows ($id)
	{
		return Product::findOrFail($id);
	}

	protected function model ()
	{
		return Product::class;
	}

	protected function softDeleted (): Builder
	{
		return $this->model()::where('deleted', true);
	}
}