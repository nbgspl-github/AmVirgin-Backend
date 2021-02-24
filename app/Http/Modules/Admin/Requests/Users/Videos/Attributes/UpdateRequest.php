<?php

namespace App\Http\Modules\Admin\Requests\Users\Videos\Attributes;

use App\Library\Utils\Extensions\Rule;

class UpdateRequest extends \Illuminate\Foundation\Http\FormRequest
{
	public function rules () : array
	{
		return [
			'title' => ['bail', 'required', 'string', 'min:1', 'max:500'],
			'description' => ['bail', 'required', 'string', 'min:1', 'max:2000'],
			'duration' => ['bail', 'required', 'regex:/^(?:(?:([01]?\d|2[0-3]):)?([0-5]?\d):)?([0-5]?\d)$/'],
			'released' => ['bail', 'required', 'date'],
			'cast' => ['bail', 'required', 'string', 'min:1', 'max:500'],
			'director' => ['bail', 'required', 'string', 'min:1', 'max:256'],
			'genre_id' => ['bail', 'required', Rule::existsPrimary(\App\Models\Video\Genre::tableName())],
			'sections.*' => ['bail', 'required'],
			'rating' => ['bail', 'required', 'numeric', 'min:0.00', 'max:5.00'],
			'pg_rating' => ['bail', 'required', Rule::in(['G', 'PG', 'PG-13', 'R', 'NC-17'])],
			'subscription_type' => ['bail', 'required', Rule::in(['free', 'paid', 'subscription'])],
			'price' => ['bail', 'sometimes', 'required_if:subscription_type,paid', 'numeric', 'min:0', 'max:10000'],
			'rank' => ['bail', 'nullable', 'gte:0', 'lt:11'],
		];
	}
}
