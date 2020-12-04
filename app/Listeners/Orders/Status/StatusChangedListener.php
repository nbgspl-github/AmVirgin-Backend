<?php

namespace App\Listeners\Orders\Status;

use App\Classes\Time;
use App\Events\Orders\Status\StatusChanged;

class StatusChangedListener
{
	public function __construct ()
	{

	}

	public function handle (StatusChanged $event)
	{
		$order = $event->eventData();
		$order->timeline()->create([
			'event' => $order->status,
			'invokedBy' => 'System',
			'timestamp' => Time::mysqlStamp()
		]);
	}
}