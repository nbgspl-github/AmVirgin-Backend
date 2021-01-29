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
			$this->paginateWithQuery($this->model->newQuery()->whereLike('name', $this->queryParameter())->latest()->where('status', 'approved'))
		);
	}

	public function pending ()
	{
		return view('admin.products.pending')->with('products',
			$this->paginateWithQuery($this->model->newQuery()->whereLike('name', $this->queryParameter())->latest()->where('status', 'pending'))
		);
	}

	public function rejected ()
	{
		return view('admin.products.rejected')->with('products',
			$this->paginateWithQuery($this->model->newQuery()->whereLike('name', $this->queryParameter())->latest()->where('status', 'rejected'))
		);
	}

	public function deleted ()
	{
		return view('admin.products.deleted')->with('products',
			$this->paginateWithQuery($this->model->newQuery()->whereLike('name', $this->queryParameter())->whereNotNull('deleted_at')->latest())
		);
	}
}