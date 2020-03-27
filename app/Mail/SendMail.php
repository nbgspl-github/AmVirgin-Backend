<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendMail extends Mailable
{
    use Queueable, SerializesModels;
    public $dataSet;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($dataSet)
    {
        $this->dataSet = $dataSet;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Forgot your password')
                    ->view('email.forgot_pass_template');
    }
}
