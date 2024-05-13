<?php

namespace App\Services;

use Illuminate\Http\Request;
use App\Models\MessagesFile;
use App\Models\SegAuditoria;
use App\Models\SegAuditoriaD;
use App\Repositories\UserRepository;
use GuzzleHttp\Client;
use Carbon\Carbon;


class AuditService
{

	private $userRepository;

	public function __construct(UserRepository $userRepository)
	{
		$this->userRepository = $userRepository;
	}

	public function store(Array $payload)
	{
		$audit = SegAuditoria::create([
			'usuario' => $this->userRepository->getUser()->usuario,
			'subsecId' => $payload["subsecid"],
			'fechaHora' => Carbon::now(),
			'ip' => $this->getClientIp(),
			'evento' => $payload["event"],
			'error' => $payload["error_code"]
		]);

		SegAuditoriaD::create([
			'auditoriaId' => $audit->auditoriaId,
			'comentarios' => $payload['msg']
		]);

		return true;
	}

	function getClientIp()
	{
		$client = new Client();

		$url = env('API_ENDPOINT');

		$response = $client->request('GET', $url, [
			'verify'  => false,
		]);

		$responseBody = json_decode($response->getBody());

		return $responseBody->ip;
	}
}
