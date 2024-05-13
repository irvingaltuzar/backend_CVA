<?php

namespace App\Jobs;

use App\Mail\ComplaintTracking;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendComplaintTrackingJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

	protected $email_list, $comment;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($email_list, $comment)
    {
        $this->email_list = $email_list;
        $this->comment = $comment;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
		Mail::to($this->email_list)
			->send(new ComplaintTracking($this->comment));
    }
}
