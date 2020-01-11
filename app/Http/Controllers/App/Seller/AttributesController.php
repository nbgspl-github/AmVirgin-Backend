<?php

namespace App\Http\Controllers\App\Seller;

use App\Category;
use App\Exceptions\ResourceNotFoundException;
use App\Http\Controllers\Base\ResourceController;
use App\Http\Resources\Attributes\AttributeResource;
use App\Interfaces\StatusCodes;
use App\Models\Attribute;
use App\Traits\FluentResponse;
use App\Traits\ValidatesRequest;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class AttributesController extends ResourceController{
	use ValidatesRequest;
	use FluentResponse;

	protected $ruleSet;

	public function __construct(){
		parent::__construct();
		$this->ruleSet = config('rules.seller.category');
	}

	public function indexFiltered($id){
		$response = null;
		try {
			$category = $this->throwIfParentNotExists($id);
			$collection = $this->provider()::all();
			$resource = $this->collectionConverter($collection);
			$response = $this->success()->setResource($resource);
			$response = ($collection->count() < 1) ? $response->status(HttpNoContent) : $response->status(HttpOkay);
		}
		catch (ResourceNotFoundException $exception) {
			$response = $this->failed()->status(HttpResourceNotFound)->message(__('strings.category.not-found'));
		}
		catch (Exception $exception) {
			$response = $this->error()->message($exception->getMessage());
		}
		finally {
			return $response->send();
		}
	}

	public function show($id){
		$response = null;
		try {
			$attribute = $this->throwIfNotExists($id);
			$response = $this->success()->setResource($this->resourceConverter($attribute));
		}
		catch (ResourceNotFoundException $exception) {
			$response = $this->failed()->status(HttpResourceNotFound)->message(__('strings.attribute.not-found'));
		}
		catch (Exception $exception) {
			$response = $this->error()->message($exception->getMessage());
		}
		finally {
			return $response->send();
		}
	}

	public function store(Request $request, $id){
		$response = null;
		$categoryId = $id;
		$attributeName = $request->name;
		try {
			$this->requestValid($request, $this->ruleSet['store']);
			$this->throwIfParentNotExists($categoryId);
			Attribute::create([
				'attributeName' => $attributeName,
				'categoryId' => $categoryId,
			]);
			$response = $this->success()->message(__('strings.category.store.success'))->status(HttpCreated);
		}
		catch (ResourceNotFoundException $exception) {
			$response = $this->failed()->message(__('strings.category.not-found'))->status(HttpResourceNotFound);
		}
		catch (Exception $exception) {
			$response = $this->error()->message($exception->getMessage());
		}
		finally {
			return $response->send();
		}
	}

	protected function provider(){
		return Attribute::class;
	}

	protected function parentProvider(){
		return Category::class;
	}

	protected function resourceConverter(Model $model = null){
		return new AttributeResource($model);
	}

	protected function collectionConverter(Collection $collection){
		return AttributeResource::collection($collection);
	}

	protected function guard(){
		// TODO: Implement guard() method.
	}
}