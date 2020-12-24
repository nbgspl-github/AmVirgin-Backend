<?php

namespace App\Http\Modules\Admin\Controllers\Web\Products;

use App\Http\Modules\Shared\Controllers\BaseController;
use App\Library\Http\WebResponse;
use App\Models\Product;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Throwable;

class ProductsController extends BaseController
{
	public function __construct ()
	{
		parent::__construct();
	}

	public function index ()
	{
		$products = Product::startQuery()->get();
		return view('admin.products.index')->with('products', $products);
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