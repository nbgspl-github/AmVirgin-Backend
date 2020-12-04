<?php

namespace App\Http\Controllers\App\Customer;

use App\Classes\Rule;
use App\Classes\Str;
use App\Constants\PageSectionType;
use App\Exceptions\ValidationException;
use App\Http\Controllers\AppController;
use App\Interfaces\Tables;
use App\Models\Product;
use App\Models\Video;
use App\Resources\GlobalSearch\ProductResultResource;
use App\Resources\GlobalSearch\VideoResultResource;
use App\Traits\ValidatesRequest;
use Throwable;

class GlobalSearchController extends AppController{
	use ValidatesRequest;
	protected array $rules;

	public function __construct(){
		parent::__construct();
		$this->rules = [
			'search' => [
				'type' => ['bail', 'required', Rule::in(PageSectionType::Entertainment, PageSectionType::Shop)],
				'category' => ['bail', 'nullable', Rule::existsPrimary(Tables::Categories)],
				'genre' => ['bail', 'nullable', Rule::existsPrimary(Tables::Genres)],
				'keyword' => ['bail', 'required', 'string', 'min:2', 'max:50'],
			],
		];
	}

	public function search(){
		$response = responseApp();
		try {
			$validated = (object)$this->requestValid(request(), $this->rules['search']);
			if (Str::equals($validated->type, PageSectionType::Entertainment)) {
				$query = Video::startQuery()->displayable();
				if (isset($validated->genre)) {
					$query->genre($validated->genre);
				}
				$query->search($validated->keyword, 'title');
				$results = VideoResultResource::collection($query->get());
				$response->status($results->count() > 0 ? HttpOkay : HttpNoContent)
					->message('Listing videos matching keywords.')
					->setValue('data', $results);
			}
			else {
				$query = Product::startQuery()->displayable();
				if (isset($validated->category)) {
					$query->categoryOrDescendant($validated->category);
				}
				$query->search($validated->keyword, 'name');
				$results = ProductResultResource::collection($query->get());
				$response->status($results->count() > 0 ? HttpOkay : HttpNoContent)
					->message('Listing products matching keywords.')
					->setValue('data', $results);
			}
		}
		catch (ValidationException $exception) {
			$response->status(HttpInvalidRequestFormat)->message($exception->getMessage());
		}
		catch (Throwable $exception) {
			$response->status(HttpServerError)->message($exception->getMessage());
		}
		finally {
			return $response->send();
		}
	}

	protected function guard(){
		return auth(self::CustomerAPI);
	}
}