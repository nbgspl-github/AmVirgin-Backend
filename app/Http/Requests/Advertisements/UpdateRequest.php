<?php

namespace App\Http\Requests\Advertisements;

use App\Exceptions\ValidationException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize (): bool
	{
		return true;
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules (): array
	{
		return [
			'subject' => ['bail', 'required', 'string', 'min:1', 'max:255'],
			'message' => ['bail', 'nullable', 'string', 'min:1', 'max:5000'],
			'banner' => ['bail', 'nullable', 'image', 'max:5124'],
			'active' => ['bail', 'sometimes', 'boolean']
		];
	}

	/**
	 * @param Validator $validator
	 * @throws ValidationException
	 */
	protected function failedValidation (Validator $validator)
	{
		throw new ValidationException($validator->errors()->first(), $validator);
	}
}
