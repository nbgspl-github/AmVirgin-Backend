<?php

namespace App\Http\Requests;

use App\Classes\Arrays;
use App\Classes\Rule;
use App\Interfaces\Tables;
use App\Models\CatalogFilter;
use App\Models\Category;
use Illuminate\Foundation\Http\FormRequest;

class StoreCategoryFilter extends FormRequest{
	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize(){
		return false;
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules(){
		return [
			'label' => ['bail', 'nullable', 'string', 'min:1', 'max:255'],
			'categoryId' => ['bail', 'required', Rule::existsPrimary(Tables::Categories)->whereNot('type', Category::Types['Root'])],
			'builtInType' => ['bail', 'required_with:builtIn', Rule::in(Arrays::values(CatalogFilter::BuiltInFilters))],
			'attributeId' => ['bail', 'required_without:builtIn', Rule::existsPrimary(Tables::Attributes)],
		];
	}
}
