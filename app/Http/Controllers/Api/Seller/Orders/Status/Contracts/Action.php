<?php

namespace App\Http\Controllers\Api\Seller\Orders\Status\Contracts;

use App\Classes\Builders\ResponseBuilder;
use App\Enums\Orders\Status;
use App\Exceptions\ActionNotAllowedException;
use App\Models\Auth\Seller;
use App\Models\SubOrder;

interface Action
{
	/**
	 * @param SubOrder $order
	 * @param Seller $seller
	 * @return bool
	 * @throws ActionNotAllowedException
	 */
	public function authorize (SubOrder $order, Seller $seller) : bool;

	public function rules () : array;

	public function allowed (SubOrder $order, Status $current, Status $next) : bool;

	public function handle (SubOrder $order, Status $next, array $extra) : ResponseBuilder;
}