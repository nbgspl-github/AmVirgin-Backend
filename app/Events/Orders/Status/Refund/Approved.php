<?php

namespace App\Events\Orders\Status\Refund;

use App\Models\Order\Order;
use App\Traits\ExposeEventData;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class Approved
{
	use Dispatchable, InteractsWithSockets, SerializesModels;
	use ExposeEventData;

	/**
	 * Create a new event instance.
	 *
	 * @param Order $order
	 */
	public function __construct (Order $order)
	{
		$this->setEventData($order);
	}

	/**
	 * Get the channels the event should broadcast on.
	 *
	 * @return Channel|array
	 */
	public function broadcastOn ()
	{
		return new PrivateChannel('channel-name');
	}
}