<?php

namespace App\Services;

use App\Models\WorkPermit;
use App\Models\WorkPermitComments;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class SendEmailService
{
	public function newWorkPermit(WorkPermit $work_permit, $type, $environment)
	{
		$mails = collect();

		// $collect = $work_permit->load(['user' => function ($q) {
		// 		return $q->with(['brand']);
		// 	}, 'type', 'brand','boss' => function ($query) use ($environment){
		// 		$query->with(['signer' => function ($q) use ($environment){
		// 			$q->with(['user_environment', 'mail'])
		// 				->whereHas('user_environment', function ($query) use ($environment){
		// 					$query->where('environment_id', $environment);
		// 				});
		// 		}])->where('deleted', 0);
		// 	}]);
		$collect = $work_permit->load([
			'user' => function ($q) {
				$q->with(['brand']);
			},
			'type',
			'brand',
			'environment',
			'boss' => function ($query) use ($environment) {
				// Usamos whereHas para asegurarnos de que solo incluya los boss que tienen un signer con user_environment válido
				$query->whereHas('signer', function ($q) use ($environment) {
					$q->whereHas('user_environment', function ($query) use ($environment) {
						$query->where('environment_id', $environment);
					});
				})->with(['signer' => function ($q) use ($environment) {
					$q->with(['user_environment' => function ($query) use ($environment) {
						$query->where('environment_id', $environment);
					}, 'mail']);
				}])->where('deleted', 0);
			}
		]);
			$collect->boss->map( function($val) use ($mails){
				return $val->signer != null ? $mails->push($val->signer->mail->mail) : false;
			});

		dispatch(new \App\Jobs\QueueJob($mails, $collect, $type, $environment, null, null))->afterResponse();
	}

	public function updateWorkPermit(WorkPermit $work_permit, $type, $environment)
	{
		$mails = collect();

		$collect = $work_permit->load([
			'user' => function ($q) {
				$q->with(['brand']);
			},
			'type',
			'brand',
			'environment',
			'boss' => function ($query) use ($environment) {
				// Usamos whereHas para asegurarnos de que solo incluya los boss que tienen un signer con user_environment válido
				$query->whereHas('signer', function ($q) use ($environment) {
					$q->whereHas('user_environment', function ($query) use ($environment) {
						$query->where('environment_id', $environment);
					});
				})->with(['signer' => function ($q) use ($environment) {
					$q->with(['user_environment' => function ($query) use ($environment) {
						$query->where('environment_id', $environment);
					}, 'mail']);
				}])->where('deleted', 0);
			}
		]);
			$collect->boss->map( function($val) use ($mails){
				return $val->signer != null ? $mails->push($val->signer->mail->mail) : false;
			});

		dispatch(new \App\Jobs\QueueJob($mails, $collect, $type, $environment, null, null))->afterResponse();
	}

	public function newWorkHighRiskPermit(WorkPermit $work_permit, $type, $environment,$usersMails)
	{
	
			$mails = collect();
			foreach($usersMails as $item){
				$mails->push($item->user->mail->mail);
			}
		
		dispatch(new \App\Jobs\QueueJob($mails, $work_permit, $type, $environment, null, null))->afterResponse();
	}

	public function lastsignWorkPermit(WorkPermit $work_permit, $type, $environment)
	{
		$mails = collect();

		$collect = $work_permit->load([
			'user' => function ($q) {
				$q->with(['brand']);
			},
			'type',
			'brand',
			'environment',
			'boss' => function ($query) use ($environment) {
				// Usamos whereHas para asegurarnos de que solo incluya los boss que tienen un signer con user_environment válido
				$query->whereHas('signer', function ($q) use ($environment) {
					$q->whereHas('user_environment', function ($query) use ($environment) {
						$query->where('environment_id', $environment);
					});
				})->with(['signer' => function ($q) use ($environment) {
					$q->with(['user_environment' => function ($query) use ($environment) {
						$query->where('environment_id', $environment);
					}, 'mail']);
				}])->where('deleted', 0);
			}
		]);
			$collect->boss->map( function($val) use ($mails){
				return $val->signer != null ? $mails->push($val->signer->mail->mail) : false;
			});
		
		dispatch(new \App\Jobs\QueueJob($mails, $collect, $type, $environment, null, null))->afterResponse();
	}

	public function newComments(WorkPermitComments $work_permit_comment, $type)
	{
		$mails = collect();
		
		$work_permit = WorkPermit::findOrFail($work_permit_comment->work_permit_id);
		$environment= $work_permit->environment_id;
	
		if ((int)$work_permit->responsable_id === (int)$work_permit_comment->user_id) {


			$collect = WorkPermitComments::with([
				'work_permit' => function ($query) use ($environment) {
					$query->with(['boss' => function ($query) use ($environment) {
						// Filtra solo los 'boss' que tienen un 'signer' con 'user_environment' válido
						$query->whereHas('signer', function ($q) use ($environment) {
							$q->whereHas('user_environment', function ($query) use ($environment) {
								$query->where('environment_id', $environment);
							});
						})->with(['signer' => function ($q) use ($environment) {
							// Incluye el 'signer' con su 'user_environment' si cumple la condición
							$q->with([
								'user_environment' => function ($query) use ($environment) {
									$query->where('environment_id', $environment);
								}, 'mail']);
						}])->where('deleted', 0);
					}]);
				},
				'user'  // Incluye la relación 'user' de WorkPermitComments
			])->findOrFail($work_permit_comment->id);

				$collect->work_permit->boss->map( function($val) use ($mails){
					return $val->signer != null ? $mails->push($val->signer->mail->mail) : false;
				});
		}else{
			$work_permit = WorkPermit::with("user.mail")->findOrFail($work_permit_comment->work_permit_id);
			if ($work_permit->user && $work_permit->user->mail) {
				$mails->push($work_permit->user->mail->mail);
			}
			$collect= WorkPermitComments::with("user")->findOrFail($work_permit_comment->id);
		}

		dispatch(new \App\Jobs\QueueJob($mails, $collect, $type, null, null, null))->afterResponse();
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
