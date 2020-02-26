<?php

namespace App\Http\Controllers\App\Seller\Attributes;

use App\Http\Controllers\Web\ExtendedResourceController;
use App\Models\Attribute;
use App\Models\Category;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Throwable;

class AttributeListController extends ExtendedResourceController {
	protected array $rules;

	public function __construct() {
		parent::__construct();
	}

	public function show(int $categoryId) {
		$response = responseApp();
		try {
			$category = Category::with('attributes')->where('id', $categoryId)->firstOrFail();
			$attributes = $category->attributes;
			$attributes->transform(function (Attribute $attribute) {
				return [
					'id' => $attribute->getKey(),
					'name' => $attribute->getName(),
				];
			});
			$response->status(HttpOkay)->message(function () use ($attributes) {
				return sprintf('Found %d attributes for the category.', $attributes->count());
			})->setValue('data', $attributes);
		}
		catch (ModelNotFoundException $exception) {
			$response->status(HttpResourceNotFound)->message('Could not find category for that key.');
		}
		catch (Throwable $exception) {
			$response->status(HttpServerError)->message($exception->getMessage());
		}
		finally {
			return $response->send();
		}
	}

	public function store(int $categoryId) {
		$response = responseApp();
		try {
			$category = Category::with('attributes')->where('id', $categoryId)->firstOrFail();
			$attributes = $category->attributes;
			$attributes->transform(function (Attribute $attribute) {
				return [
					'id' => $attribute->getKey(),
					'name' => $attribute->getName(),
				];
			});
			$response->status(HttpOkay)->message(function () use ($attributes) {
				return sprintf('Found %d attributes for the category.', $attributes->count());
			})->setValue('data', $attributes);
		}
		catch (ModelNotFoundException $exception) {
			$response->status(HttpResourceNotFound)->message('Could not find category for that key.');
		}
		catch (Throwable $exception) {
			$response->status(HttpServerError)->message($exception->getMessage());
		}
		finally {
			return $response->send();
		}
	}

	protected function guard() {
		return auth('seller-api');
	}
}