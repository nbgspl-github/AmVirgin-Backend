<?php

namespace App\Http\Controllers\App\Customer;

use App\Classes\Str;
use App\Constants\PageSectionType;
use App\Http\Controllers\Web\ExtendedResourceController;
use App\Interfaces\Tables;
use App\Models\Product;
use App\Models\Video;
use App\Resources\Search\Customer\Entertainment\ProductResource;
use App\Resources\Search\Customer\Entertainment\VideoResource;
use App\Traits\ValidatesRequest;
use Illuminate\Validation\Rule;
use Throwable;

class GlobalSearchController extends ExtendedResourceController {
	use ValidatesRequest;
	protected array $rules;

	public function __construct() {
		parent::__construct();
		$this->rules = [
			'search' => [
				'type' => ['bail', 'required', Rule::in(PageSectionType::Entertainment, PageSectionType::Shop)],
				'categoryId' => ['bail', 'nullable', Rule::exists(Tables::Categories, 'id')],
				'key' => ['bail', 'required', 'string', 'min:2', 'max:50'],
			],
		];
	}

	public function search() {
		$response = responseApp();
		try {
			$validated = (object)$this->requestValid(request(), $this->rules['search']);
			if (Str::equals($validated->type, PageSectionType::Entertainment)) {
				$contents = Video::where([
					['name', 'LIKE', "%{$validated->key}%"],
					['pending', false],
				])->get();
				$contents = VideoResource::collection($contents);
				$response->status(HttpOkay)->message(sprintf('Listing %d videos for your search query.', count($contents)))->setValue('data', $contents);
			}
			else {
				$criteria = [
					['name', 'LIKE', "%{$validated->key}%"],
					['visibility', true],
					['draft', false],
					['deleted', false],
				];
				if (isset($validated->categoryId)) {
					$criteria[] = ['categoryId', $validated->categoryId];
				}
				$contents = Product::where($criteria)->get();
				$contents = ProductResource::collection($contents);
				$response->status(HttpOkay)->message(sprintf('Listing %d products for your search query.', count($contents)))->setValue('data', $contents);
			}
		}
		catch (Throwable $exception) {
			$response->status(HttpServerError)->message($exception->getMessage());
		}
		finally {
			return $response->send();
		}
	}

	protected function guard() {
		return auth('customer-api');
	}
}