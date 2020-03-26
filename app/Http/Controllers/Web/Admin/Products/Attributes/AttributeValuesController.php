<?php

namespace App\Http\Controllers\Web\Admin\Products\Attributes;

use App\Http\Controllers\BaseController;
use App\Models\Attribute;
use App\Traits\ValidatesRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Throwable;

class AttributeValuesController extends BaseController{
	use ValidatesRequest;

	protected array $rules;

	public function __construct(){
		parent::__construct();
		$this->rules = [
			'store' => [

			],
			'update' => [

			],
		];
	}

	public function edit($attributeId){
		$response = responseWeb();
		try {
			$attribute = Attribute::retrieveThrows($attributeId);
		}
		catch (ModelNotFoundException $exception) {
			$res
		}
		catch (Throwable $exception) {

		}
		finally {

		}
	}

	public function store($attributeId){

	}

	public function update($attributeId){

	}
}