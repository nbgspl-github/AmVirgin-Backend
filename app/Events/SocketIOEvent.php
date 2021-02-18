<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SocketIOEvent implements ShouldBroadcastNow
{
	use Dispatchable, InteractsWithSockets, SerializesModels;

	/**
	 * Create a new event instance.
	 *
	 * @return void
	 */
	public function __construct ()
	{
		//
	}

	/**
	 * Get the channels the event should broadcast on.
	 *
	 * @return \Illuminate\Broadcasting\Channel|array
	 */
	public function broadcastOn ()
	{
		return new PrivateChannel('channel-name');
	}

	/**
	 * Get the data to broadcast.
	 *
	 * @return array
	 * @author Author
	 *
	 */
	public function broadcastWith () : array
	{
		return [
			'actionId' => 'ABC',
			'actionData' => 'XYZ',

		];
	}
}