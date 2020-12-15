<?php

namespace App\Http\Controllers\Api\Seller\Orders\Status;

use App\Http\Controllers\Api\ApiController;
use App\Models\SubOrder;
use App\Traits\ValidatesRequest;

abstract class AbstractStatusController extends ApiController
{
	use ValidatesRequest;

	/**
	 * @var array|\array[][]
	 */
	protected array $rules;

	/**
	 * @var \Closure[]|array
	 */
	protected array $closures;

	public function __construct ()
	{
		parent::__construct();
		$this->middleware(AuthSeller);
		$this->rules = [
			'update' => $this->rulesByStatus(request('status'))
		];
		$this->closures = $this->closures();
	}

	protected function statusAllowed (string $current, string $next): bool
	{
		return true;
	}

	protected function performStatusUpdate (SubOrder $order, string $new, array $payload)
	{
		/**
		 * Call the stored closure for additional processing on the order.
		 */
		if (isset($this->closures[$new])) {
			$closure = $this->closures[$new];
			call_user_func($closure, $order, $payload);
		}
		$order->update([
			'status' => $new
		]);
	}

	protected abstract function rulesByStatus ($status): array;

	protected abstract function closures (): array;

	protected function guard ()
	{
		return auth(self::SELLER_API);
	}
}