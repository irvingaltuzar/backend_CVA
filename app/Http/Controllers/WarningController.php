<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreWarningRequest;
use App\Models\Warning;
use App\Models\WarningFile;
use App\Repositories\UserRepository;
use App\Services\FileUploaderService;
use Illuminate\Http\Request;

class WarningController extends Controller
{
	private $userRepository, $fileUploader;

	public function __construct(UserRepository $userRepository, FileUploaderService $fileUploader)
	{
		$this->userRepository = $userRepository;
		$this->fileUploader = $fileUploader;
	}

    public function store(StoreWarningRequest $request)
	{
		$warning = Warning::create([
			'to' => $request['user_id'],
			'date' => $request['date'],
			'title' => $request['title'],
			'message' => $request['description'],
			'penalty' => $request['amount'],
			'time_to_penalty' => $request['expiration_date'],
			'sended_by_id' => $this->userRepository->getUser()->user[0]->id,
			'warning_type_id' => $request['cat_warning_type_id']
		]);

		if (!!$request['files']) {
			foreach ($request['files'] as $file) {

				$file = $this->fileUploader->store($file, [
							'type' => 'Warnings',
							'id' => $warning->id
						]);

				$this->storeWarningFile($warning->id, $file['filename']);
			}
		} else {
			false;
		}

		$warning->load(['sendedBy', 'toName', 'type']);

		return response()->json($warning);
	}

	public function storeWarningFile(int $id, String $filename)
	{
		WarningFile::create([
			'warning_id' => $id,
			'file' => $filename
		]);
	}

	public function fetchData()
	{
		$warnings = Warning::with(['sendedBy', 'toName', 'type'])
						->where('deleleted', 0)
						->get();

		return response()->json($warnings);
	}

	public function fetchDataByUser()
	{
		$warnings = Warning::with(['sendedBy', 'toName', 'type'])
						->whereIn('to', $this->userRepository->getUser()->user->pluck('id'))
						->where('deleleted', 0)
						->get();

		return response()->json($warnings);
	}
}
