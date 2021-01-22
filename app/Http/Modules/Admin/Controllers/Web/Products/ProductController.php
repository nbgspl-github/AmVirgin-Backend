<?php

namespace App\Http\Modules\Admin\Controllers\Web\Products;

use App\Models\Product;

class ProductController extends \App\Http\Modules\Admin\Controllers\Web\WebController
{
	/**
	 * @var Product
	 */
	protected $model;

	public function __construct ()
	{
		parent::__construct();
		$this->middleware(AUTH_ADMIN);
		$this->model = app(Product::class);
	}

	public function approved ()
	{
		return view('admin.products.approved')->with('products',
			$this->paginateWithQuery($this->model->newQuery()->where('approved', true)->latest())
		);
	}

	public function pending ()
	{
		return view('admin.products.pending')->with('products',
			$this->paginateWithQuery($this->model->newQuery()->whereLike('name', $this->queryParameter())->latest()->groupBy('group')->where('approved', false))
		);
	}

	public function rejected ()
	{
		return view('admin.products.rejected')->with('products',
			$this->paginateWithQuery($this->model->newQuery()->where('approved', true))
		);
	}

	public function deleted ()
	{
		return view('admin.products.deleted')->with('products',
			$this->paginateWithQuery($this->model->newQuery()->whereNotNull('deleted_at'))
		);
	}
}