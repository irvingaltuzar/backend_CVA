<?php

namespace App\Services;

use App\Models\WorkPermit;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class SendEmailService
{
	public function newWorkPermit(WorkPermit $work_permit, $type, $environment)
	{
		$mails = collect();

		$collect = $work_permit->load(['user' => function ($q) {
				return $q->with(['brand']);
			}, 'type', 'boss' => function ($query) use ($environment){
				$query->with(['signer' => function ($q) use ($environment){
					$q->with(['user_environment', 'mail'])
						->whereHas('user_environment', function ($query) use ($environment){
							$query->where('environment_id', $environment);
						});
				}])->where('deleted', 0);
			}]);

			$collect->boss->map( function($val) use ($mails){
				return $val->signer != null ? $mails->push($val->signer->mail->mail) : false;
			});

		dispatch(new \App\Jobs\QueueJob($mails, $collect, $type, $environment, null, null))->afterResponse();
	}

	public function newUser($mails, $request, $pwd)
	{
		dispatch(new \App\Jobs\WelcomeMessageJob($mails, $request, $pwd))->afterResponse();
	}

	public function sendStatus(WorkPermit $work_permit, $type, $environment, $start = null, $end = null)
	{
		$collect = $work_permit->load(['user.mail']);

		dispatch(new \App\Jobs\QueueJob($collect->user->mail->mail, $collect, $type, $environment, $start, $end))->afterResponse();
	}

	public function complaintConfirm(String $comments, String $mail)
	{
		dispatch(new \App\Jobs\SendComplaintTrackingJob($mail, $comments))->afterResponse();
	}

	public function sendTokenPassword(Object $mails, String $token)
	{
		dispatch(new \App\Jobs\SendTokenJob($mails, $token))->afterResponse();
	}
}
