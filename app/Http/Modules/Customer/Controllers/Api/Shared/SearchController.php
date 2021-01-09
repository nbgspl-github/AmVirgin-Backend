<?php

namespace App\Http\Modules\Customer\Controllers\Api\Shared;

use App\Http\Modules\Customer\Requests\Search\SearchRequest;
use App\Library\Enums\Common\PageSectionType;
use App\Library\Utils\Extensions\Str;
use App\Models\Product;
use App\Models\Video\Video;
use App\Resources\GlobalSearch\ProductResultResource;
use App\Resources\GlobalSearch\VideoResultResource;
use Illuminate\Http\JsonResponse;

class SearchController extends \App\Http\Modules\Customer\Controllers\Api\ApiController
{
	public function __construct ()
	{
		parent::__construct();
	}

	public function search (SearchRequest $request) : JsonResponse
	{
		$results = null;
		if (Str::equals($request->type, PageSectionType::Entertainment)) {
			$query = Video::startQuery()->displayable();
			if (isset($request->genre)) {
				$query->genre($request->genre);
			}
			$query->search($request->keyword, 'title');
			$results = VideoResultResource::collection($query->get());
		} else {
			$query = Product::startQuery()->displayable();
			if (isset($request->category)) {
				$query->categoryOrDescendant($request->category);
			}
			$query->search($request->keyword, 'name');
			$results = ProductResultResource::collection($query->get());
		}
		return responseApp()->prepare(
			$results
		);
	}
}