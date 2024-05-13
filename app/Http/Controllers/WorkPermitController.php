<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthorizePermitRequest;
use App\Http\Requests\StoreWorkPermitRequest;
use Illuminate\Support\Facades\Storage;
use App\Services\FileUploaderService;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Auth;
use App\Models\WorkPermitPdf;
use Illuminate\Http\Request;
use App\Models\WorkPermit;
use App\Models\User;
use App\Models\WorkPermitFile;
use App\Services\SendEmailService;
use Carbon\Carbon;
use PDF;
use Ramsey\Uuid\Uuid;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class WorkPermitController extends Controller
{
	private $userRepository, $fileUploader, $sendEmail;

	public function __construct(UserRepository $userRepository, FileUploaderService $fileUploader, SendEmailService $sendEmail)
	{
			$this->userRepository = $userRepository;
			$this->fileUploader = $fileUploader;
			$this->sendEmail = $sendEmail;
	}

    public function store(StoreWorkPermitRequest $request)
	{
		$user = User::where('SEG_USUARIOS_usuarioId', Auth::user()->usuarioId)->first();

		$work_permit = WorkPermit::create([
			'cat_work_permit_type_id' => $request['cat_work_permit_type_id'],
			'qr_code' => Uuid::uuid4(),
			'description' => $request['description'],
			'warning_phone' => $request['warning_phone'],
			'start' => $request['permit_date'],
			'end' => $request['end_date'],
			'responsable_id' => $user->id,
			'authorized_by_id' => 1,
			'created_at' => Carbon::now(),
		]);

		$this->sendEmail->newWorkPermit($work_permit, 1, $request->current_environment);

		if (!!$request['files']) {
			foreach ($request['files'] as $file) {
				$file = $this->fileUploader->upload($file, [
							'type' => 'WorkPermits',
							'id' => $work_permit->id
						]);

				$this->storeWorkPermitFile($work_permit->id, $file['filename']);
			}
		} else {
			false;
		}

		$work_permit->load(['user.userSec', 'type', 'authorizedBy']);

		return response()->json($work_permit, JSON_UNESCAPED_UNICODE);
	}

	public function storeWorkPermitFile(int $work_permit_id, String $filename)
	{
		WorkPermitFile::create([
			'work_permit_id' => $work_permit_id,
			'file' => $filename
		]);
	}

	public function dt(int $current_environment)
	{
		$brands = $this->userRepository->getBrandsByEnvironment($current_environment);

		$permits = WorkPermit::with(['user.userSec', 'type', 'files'])
					->whereHas('boss', function ($query) {
						$query->where('users_id', $this->userRepository->getUser()->user[0]->id)->where('deleted', false);
					})
					->whereHas('user', function ($q) use ($brands){
						return $q->whereIn('cat_brand_id', $brands);
					})
					->where('deleted', false)
					->orderBy('created_at', 'desc')
					->get();

		return response()->json($permits);
	}

	public function fetchSupplierPermit()
	{
		$permits = WorkPermit::with(['user.userSec', 'type', 'authorizedBy'])
					->where('responsable_id', $this->userRepository->getUser()->user[0]->id)
					->where('deleted', 0)
					->get();

		return response()->json($permits);
	}

	public function fetchSecurityPermit(int $current_environment)
	{
		$brands = $this->userRepository->getBrandsByEnvironment($current_environment);

		$permits = WorkPermit::with(['user.userSec', 'type', 'authorizedBy'])
					->whereHas('user', function ($q) use ($brands){
						return $q->whereIn('cat_brand_id', $brands);
					})
					->where('authorized', true)
					->where('deleted', 0)
					->get();

		return response()->json($permits);
	}

	public function authorizePermit(AuthorizePermitRequest $request)
	{
		$signature = $this->userRepository->getSignature($this->userRepository->getUser()->user[0]->id, $this->userRepository->getUser()->usuario);

		if (!!!$signature) {
			return response()->json([
				'message' => 'No tienes firma registrada.'
			], 401);
		} else {
			$work_permit = WorkPermit::where('id', $request->id)
								->firstOrFail();

			$work_permit->authorized = true;
			$work_permit->start = $request->start;
			$work_permit->end = $request->end;
			$work_permit->authorized_by_id = $this->userRepository->getUser()->user[0]->id;
			$work_permit->save();

			$this->sendEmail->sendStatus($work_permit, 2, $request->environment, $request->start, $request->end);

			return response()->json([
				'work_permit' => $work_permit
			]);
		}
	}

	public function cancelPermit(Request $request)
	{
		$work_permit = WorkPermit::where('id', $request->id)
							->firstOrFail();

		$work_permit->authorized = 2;
		$work_permit->authorized_by_id = $this->userRepository->getUser()->user[0]->id;
		$work_permit->comments = $request->comment;
		$work_permit->save();

		$this->sendEmail->sendStatus($work_permit, 3, $request->environment);

		return response()->json([
			'work_permit' => $work_permit
		]);
	}

	public function showPdf(Request $request)
	{
		$data = WorkPermit::with(['user' => function ($q) {
			$q->with(['phones', 'mail', 'brand_environment.environment']);
		}, 'type', 'authorizedBy', 'files'])->find($request->id);

		$is_saved = WorkPermitPdf::where('work_permit_id', $data->id)->first();

		if (!!!$is_saved) {
			return $this->saveFile($data);
		} else {
			return response()->json([
				'url' => Storage::disk('public')->url("pdf/work_permit/{$is_saved->file}")
			]);
		}
	}

	public function saveFile($data)
	{
		$owner_signature = $this->userRepository->getSignaturePDF($data->user->id, $data->user->userSec->usuario);

		$authorized_by_signature = $this->userRepository->getSignaturePDF($data->authorizedBy->id, $data->authorizedBy->userSec->usuario);

		$authorized_by_url = parse_url($authorized_by_signature['url']);

		$owner_url = parse_url($owner_signature['url']);

		$files = $data->files;

		$pdf = PDF::loadView('pdf.show',  [
			'data' => $data,
			'id' => $data->qr_code,
			'logo' => $data->user->brand_environment->environment->logo,
			'owner_signature' => $owner_url['path'],
			'authorized_by_signature' => $authorized_by_url['path'],
			'date' => Carbon::now()->translatedFormat('j M Y'),
			'files' => $files
			], [], [
				'format' => 'A4',
				'orientation' => 'L'
			]);


		$filename = $data->id.'-'.time().'_'.date('Y-m-d').'.pdf';

		$file = $this->storeFile($data->id, $filename);

		$pdf->save(public_path("/storage/pdf/work_permit/{$filename}"));

		return response()->json([
			'url' => Storage::disk('public')->url("pdf/work_permit/{$filename}")
		]);
	}

	public function testPDF()
	{
		$data = WorkPermit::with(['user' => function ($q) {
			$q->with(['phones', 'mail', 'brand_environment.environment']);
		}, 'type', 'authorizedBy', 'files'])->find(89);

		$owner_signature = $this->userRepository->getSignaturePDF($data->user->id, $data->user->userSec->usuario);

		$authorized_by_signature = $this->userRepository->getSignaturePDF($data->authorizedBy->id, $data->authorizedBy->userSec->usuario);

		$authorized_by_url = parse_url($authorized_by_signature['url']);

		$files = $data->files;

		$owner_url = parse_url($owner_signature['url']);
		$pdf = PDF::loadView('pdf.show',  [
			'data' => $data,
			'id' => $data->qr_code,
			'logo' => $data->user->brand_environment->environment->logo,
			'owner_signature' => $owner_url['path'],
			'authorized_by_signature' => $authorized_by_url['path'],
			'date' => Carbon::now()->translatedFormat('j M Y'),
			'files' => $files
			], [], [
				'format' => 'A4',
				'orientation' => 'L'
			]);

		return $pdf->stream();


		$filename = $data->id.'-'.time().'_'.date('Y-m-d').'.pdf';

		$file = $this->storeFile($data->id, $filename);

		$pdf->save(public_path("/storage/pdf/work_permit/{$data->user->userSec->usuario}/{$filename}"));

		return response()->json([
			'url' => Storage::disk('public')->url("work_permit/{{$data->user->userSec->usuario}/{$filename}")
		]);
	}

	public function storeFile(int $id, String $filename)
	{
		$file = WorkPermitPdf::create([
			'work_permit_id' => $id,
			'file' => $filename
		]);

		return $file;
	}
}
