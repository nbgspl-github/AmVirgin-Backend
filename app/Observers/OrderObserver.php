<?php

namespace App\Observers;

use App\Models\Order;

final class OrderObserver {
	public function creating (Order $order) : void {
		$order->orderNumber = $this->makeOrderNumber();
	}

	protected function makeOrderNumber () {
		return sprintf('AVG%d%d', time(), rand(1, 1000));
	}
}