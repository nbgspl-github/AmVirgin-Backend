<?php

namespace App\Http\Modules\Admin\Requests\Users\Series;

use App\Library\Utils\Extensions\Rule;

class StoreRequest extends \Illuminate\Foundation\Http\FormRequest
{
	public function rules () : array
	{
		return [
			'title' => ['bail', 'required', 'string', 'min:1', 'max:500'],
			'description' => ['bail', 'required', 'string', 'min:1', 'max:2000'],
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

	public function validated () : array
	{
		$validated = parent::validated();
		return array_merge($validated, [
			'type' => \App\Library\Enums\Videos\Types::Series,
			'rank' => $validated['rank'] ?? 0,
			'sections' => $validated['sections'] ?? []
		]);
	}
}
