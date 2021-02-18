<?php

namespace App\Http\Modules\Seller\Requests\Auth\Profile;

class UpdateRequest extends \Illuminate\Foundation\Http\FormRequest
{
	public function rules () : array
	{
		return [
			'name' => ['bail', 'required', 'string', 'min:2', 'max:256'],
			'businessName' => ['bail', 'required', 'string', 'min:2', 'max:256'],
			'description' => ['bail', 'nullable', 'string', 'min:1', 'max:2000'],
			'pinCode' => ['bail', 'nullable', 'string', 'min:1', 'max:2000'],
			'addressFirstLine' => ['bail', 'nullable', 'string', 'min:1', 'max:256'],
			'addressSecondLine' => ['bail', 'nullable', 'string', 'min:1', 'max:256'],
			'countryId' => ['bail', 'nullable', \App\Models\Country::exists()],
			'stateId' => ['bail', 'nullable', \App\Models\State::exists()],
			'cityId' => ['bail', 'nullable', \App\Models\City::exists()],
		];
	}
}