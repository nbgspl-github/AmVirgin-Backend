<?php

namespace App\Http\Modules\Seller\Requests\Orders;

use App\Library\Enums\Orders\Status;
use App\Library\Utils\Extensions\Rule;

class IndexRequest extends \Illuminate\Foundation\Http\FormRequest
{
	public function rules () : array
	{
		return [
			'status' => ['bail', 'sometimes', Rule::in(Status::getValues())],
			'key' => ['bail', 'sometimes', Rule::existsPrimary(\App\Models\Order\SubOrder::tableName())]
		];
	}
}