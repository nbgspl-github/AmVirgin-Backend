<?php

namespace App\Http\Modules\Seller\Controllers\Api\Orders\Status\Contracts;

use App\Library\Enums\Orders\Status;
use App\Library\Http\AppResponse;
use App\Models\Auth\Seller;
use App\Models\Order\SubOrder;

interface Action
{
	/**
	 * @param SubOrder $order
	 * @param Seller $seller
	 * @return bool
	 */
	public function authorize (SubOrder $order, Seller $seller) : bool;

	/**
	 * @return array
	 */
	public function rules () : array;

	/**
	 * @param SubOrder $order
	 * @param Status $action
	 * @param Status $next
	 * @return bool
	 */
	public function allowed (SubOrder $order, Status $action, Status $next) : bool;

	/**
	 * @param SubOrder $order
	 * @param Status $next
	 * @param array $extra
	 * @return AppResponse
	 */
	public function handle (SubOrder $order, Status $next, array $extra) : AppResponse;
}