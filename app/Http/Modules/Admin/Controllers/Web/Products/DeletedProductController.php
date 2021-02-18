<?php

namespace App\Http\Modules\Admin\Controllers\Web\Products;

use App\Http\Modules\Admin\Repository\Products\Contracts\ProductRepository;

class DeletedProductController extends \App\Http\Modules\Admin\Controllers\Web\WebController
{
	/**
	 * @var ProductRepository
	 */
	protected $repository;

	public function __construct (ProductRepository $repository)
	{
		parent::__construct();
		$this->repository = $repository;
	}

	public function index ()
	{
		return view('admin.products.deleted.index')->with('products',
			$this->repository->getWithSellerDeletedProductsPaginated($this->paginationChunk())
		);
	}
}