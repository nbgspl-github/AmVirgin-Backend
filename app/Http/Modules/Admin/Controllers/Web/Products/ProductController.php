<?php

namespace App\Http\Modules\Admin\Controllers\Web\Products;

use App\Http\Modules\Admin\Repository\Products\Contracts\ProductRepository;
use App\Library\Http\WebResponse;
use App\Models\Product;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Throwable;

class ProductController extends \App\Http\Modules\Admin\Controllers\Web\WebController
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
		return view('admin.products.index')->with('products', $this->repository->allProductsPaginated());
	}

	public function approve ($id)
	{
		$response = responseWeb();
		try {
			$product = Product::findOrFail($id);
			$product->update([
				'approved' => true,
				'approvedBy' => auth('admin')->id(),
				'approvedAt' => date('Y-m-d H:i:s')
			]);
			$response->back()->success('Product approved successfully.');
		} catch (ModelNotFoundException $exception) {
			$response->error($exception->getMessage())->data(request()->all())->back();
		} catch (Throwable $exception) {
			$response->error($exception->getMessage())->data(request()->all())->back();
		} finally {
			if ($response instanceof WebResponse)
				return $response->send();
			else
				return $response;
		}
	}
}