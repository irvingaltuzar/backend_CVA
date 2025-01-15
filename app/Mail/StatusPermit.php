<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class StatusPermit extends Mailable
{
    use Queueable, SerializesModels;

	public $data, $permit, $start, $end;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data, $permit, $start, $end)
    {
        $this->data = $data;
        $this->permit = $permit;
        $this->start = $start;
        $this->end = $end;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
		if ($this->data === 1) {
			return $this->markdown('emails.permits.status')->subject('Permiso autorizado.');
		} else if($this->data === 2) {
			return $this->markdown('emails.permits.status')->subject('Permiso rechazado.');
		}else {
			return $this->markdown('emails.permits.status')->subject('Permiso Reasignado.');
		}
    }
}
