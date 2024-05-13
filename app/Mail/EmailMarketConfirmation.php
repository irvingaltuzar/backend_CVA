<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EmailMarketConfirmation extends Mailable
{
    use Queueable, SerializesModels;

	public $data, $pwd;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data, $pwd)
    {
		$this->data = $data;
		$this->pwd = $pwd;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.users.confirmation_market')->subject('¡Registro exitoso!.');
    }
}
