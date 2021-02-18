<?php

namespace App\Http\Modules\Customer\Requests\Search;

use App\Library\Enums\Common\PageSectionType;
use App\Library\Utils\Extensions\Rule;

class SearchRequest extends \Illuminate\Foundation\Http\FormRequest
{
	public function rules () : array
	{
		return [
			'type' => ['bail', 'required', Rule::in(PageSectionType::Entertainment, PageSectionType::Shop)],
			'category' => ['bail', 'nullable', Rule::existsPrimary(\App\Models\Category::tableName())],
			'genre' => ['bail', 'nullable', Rule::existsPrimary(\App\Models\Video\Genre::tableName())],
			'keyword' => ['bail', 'required', 'string', 'min:2', 'max:50'],
		];
	}
}