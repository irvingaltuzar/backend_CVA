<?php

namespace App\Http\Controllers;

use App\Models\Signature;
use App\Repositories\UserRepository;
use App\Services\AuditService;
use App\Services\SignatureUploaderService;
use Illuminate\Http\Request;

class SignatureController extends Controller
{
	private $signatureUploader, $userRepository, $auditService;

	public function __construct(SignatureUploaderService $signatureUploader, UserRepository $userRepository, AuditService $auditService)
	{
		$this->signatureUploader = $signatureUploader;
		$this->userRepository = $userRepository;
		$this->auditService = $auditService;
	}

    public function store(Request $request)
	{
		$signature = $this->signatureUploader->upload([
			'user_name' => $this->userRepository->getUser()->usuario,
			'signature' => $request->signature
		]);

		$data = Signature::create([
			'users_id' => $this->userRepository->getUser()->user[0]->id,
			'file' => $signature['filename']
		]);

		$this->auditService->store([
			'event' => 'SignatureController@store',
			'subsecid' => 3,
			'error' => false,
			'error_code' => 0,
			'msg' => 'Se guardó la firma'
		]);

		return response()->json($signature['url']);
	}

	public function get()
	{
		$signature = $this->userRepository->getSignature();

		return response()->json([
			'signature' => $signature['signature'],
			'url' => $signature['url']
		]);
	}
}
