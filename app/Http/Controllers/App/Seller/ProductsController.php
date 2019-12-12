<?php

namespace App\Http\Controllers\App\Seller;

use App\Exceptions\ValidationException;
use App\Http\Controllers\Base\ResourceController;
use App\Models\Product;
use App\Traits\FluentResponse;
use App\Traits\ValidatesRequest;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class ProductsController extends ResourceController{
	use ValidatesRequest;
	use FluentResponse;

	protected $rules;

	public function __construct(){
		$this->rules = config();
	}

	public function index(){

	}

	public function edit($id){

	}

	public function store(Request $request){
		$response = null;
		try {
			$this->requestValid($request, $this->rules['store']);
		}
		catch (ValidationException $exception) {

		}
		finally {

		}
	}

	public function update(Request $request, $id){

	}

	public function patch(Request $request, $id){

	}

	public function delete($id){

	}

	protected function parentProvider(){
		return null;
	}

	protected function provider(){
		return Product::class;
	}

	protected function resourceConverter(Model $model){

	}

	protected function collectionConverter(Collection $collection){

	}
}