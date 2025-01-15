<?php

namespace App\Jobs;

use App\Mail\EmailMarketConfirmation;
use App\Mail\WelcomeMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class WelcomeMessageJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

	protected $email_list, $data, $pwd;

    /**
     * Create a new job instance.
     *
     * @return void
     */
	public function __construct($email_list, $data, $pwd)
    {
        $this->email_list = $email_list;
		$this->data = $data;
		$this->pwd = $pwd;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
		if (!empty($this->data['environments'])) {
            // Si hay al menos un entorno presente en el array, envía el correo de bienvenida
            Mail::to($this->email_list)
                ->send(new WelcomeMessage($this->data, $this->pwd));
        }
        // else    
        // if (in_array(2, $this->data['environments'])) {
        //     // Si el entorno 2 está presente en el array, envía el correo de confirmación de mercado
        //     Mail::to($this->email_list)
        //         ->send(new EmailMarketConfirmation($this->data, $this->pwd));
        // }
    }
}
