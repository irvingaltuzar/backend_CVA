<?php

namespace App\Jobs;

use App\Mail\NewPermit;
use App\Mail\StatusPermit;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class QueueJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

	protected $email_list, $data, $type, $environment, $start, $end;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($email_list, $data, $type, $environment, $start, $end)
    {
        $this->email_list = $email_list;
		$this->data = $data;
		$this->type = $type;
		$this->environment = $environment;
		$this->start = $start;
		$this->end = $end;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
		if ($this->type === 1) {
				Mail::to($this->email_list)
					->send(new NewPermit($this->data));
			// if ($this->environment == 1) {
			// 	Mail::to($this->email_list)
			// 		->send(new NewPermit($this->data));
			// } else{
			// 	Mail::to($this->email_list)
			// 		->send(new NewPermit($this->data));
			// }
		}

		if ($this->type === 2) {
			Mail::to($this->email_list)
				->send(new StatusPermit(1, $this->data, $this->start, $this->end));
		}

		if ($this->type === 3) {
			Mail::to($this->email_list)
				->send(new StatusPermit(2, $this->data, null, null));
		}
		if ($this->type === 5) {
			Mail::to($this->email_list)
				->send(new StatusPermit(2, $this->data, null, null));
		}
    }
}