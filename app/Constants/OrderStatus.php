<?php

namespace App\Constants;

class OrderStatus {
	const Placed = 'placed';
	const Dispatched = 'dispatched';
	const Delivered = 'delivered';
	const Cancelled = 'cancelled';
	const RefundProcessing = 'refund-processing';
	const PendingDispatch = 'pending-dispatch';
}