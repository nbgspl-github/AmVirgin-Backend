<?php

namespace App\Http\Modules\Customer\Controllers\Api\Shared;

use App\Exceptions\ValidationException;
use App\Library\Enums\Common\PageSectionType;
use App\Library\Enums\Common\Tables;
use App\Library\Utils\Extensions\Rule;
use App\Library\Utils\Extensions\Str;
use App\Models\Product;
use App\Models\Video\Video;
use App\Resources\GlobalSearch\ProductResultResource;
use App\Resources\GlobalSearch\VideoResultResource;
use App\Traits\ValidatesRequest;
use Illuminate\Http\JsonResponse;
use Throwable;

class SearchController extends \App\Http\Modules\Customer\Controllers\Api\ApiController
{
	use ValidatesRequest;

	protected array $rules;

	public function __construct ()
	{
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

	public function search () : JsonResponse
	{
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
				$response->status($results->count() > 0 ? \Illuminate\Http\Response::HTTP_OK : \Illuminate\Http\Response::HTTP_NO_CONTENT)
					->message('Listing videos matching keywords.')
					->setValue('data', $results);
			} else {
				$query = Product::startQuery()->displayable();
				if (isset($validated->category)) {
					$query->categoryOrDescendant($validated->category);
				}
				$query->search($validated->keyword, 'name');
				$results = ProductResultResource::collection($query->get());
				$response->status($results->count() > 0 ? \Illuminate\Http\Response::HTTP_OK : \Illuminate\Http\Response::HTTP_NO_CONTENT)
					->message('Listing products matching keywords.')
					->setValue('data', $results);
			}
		} catch (ValidationException $exception) {
			$response->status(\Illuminate\Http\Response::HTTP_BAD_REQUEST)->message($exception->getMessage());
		} catch (Throwable $exception) {
			$response->status(\Illuminate\Http\Response::HTTP_INTERNAL_SERVER_ERROR)->message($exception->getMessage());
		} finally {
			return $response->send();
		}
	}
}