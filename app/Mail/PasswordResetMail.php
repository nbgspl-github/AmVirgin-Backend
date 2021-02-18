<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PasswordResetMail extends Mailable
{
	use Queueable, SerializesModels;

	protected $url;

	/**
	 * Create a new message instance.
	 *
	 * @param string $url
	 */
	public function __construct (string $url)
	{
		$this->url = $url;
	}

	/**
	 * Build the message.
	 *
	 * @return $this
	 */
	public function build ()
	{
		return $this->view('customer.auth.passwords.email')->with('url', $this->url);
	}
}