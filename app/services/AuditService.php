<?php

namespace App\Services;

use Illuminate\Http\Request;
use App\Models\MessagesFile;
use App\Models\SegAuditoria;
use App\Models\SegAuditoriaD;
use App\Repositories\UserRepository;
use GuzzleHttp\Client;
use Carbon\Carbon;
use GuzzleHttp\Exception\RequestException;

class AuditService
{

	private $userRepository;

	public function __construct(UserRepository $userRepository)
	{
		$this->userRepository = $userRepository;
	}

	public function store(Array $payload)
	{
		$ip = $this->getIPAddress();
		$audit = SegAuditoria::create([
			'usuario' => $this->userRepository->getUser()->usuario,
			'subsecId' => $payload["subsecid"],
			'fechaHora' => Carbon::now(),
			'ip' => $ip,
			'evento' => $payload["event"],
			'error' => $payload["error_code"]
		]);

		SegAuditoriaD::create([
			'auditoriaId' => $audit->auditoriaId,
			'comentarios' => $payload['msg']
		]);

		return true;
	}

	function getIPAddress()
	{
		//whether ip is from the share internet
		if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
			$ip = $_SERVER['HTTP_CLIENT_IP'];
		}
		//whether ip is from the proxy
		elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
			$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		}
		//whether ip is from the remote address
		else {
			$ip = $_SERVER['REMOTE_ADDR'];
		}
		return $ip;
	}

}
