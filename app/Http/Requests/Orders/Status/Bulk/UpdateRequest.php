<?php

namespace App\Http\Requests\Orders\Status\Bulk;

use App\Library\Utils\Extensions\Str;
use App\Models\Order\SubOrder;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Collection;

class UpdateRequest extends FormRequest
{
	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize () : bool
	{
		return true;
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules () : array
	{
		return [

		];
	}

	public function orders () : Collection
	{
		return SubOrder::startQuery()->withRelations('seller')->whereIn('id', Str::split(',', request('key', Str::Empty)))->get();
	}
}