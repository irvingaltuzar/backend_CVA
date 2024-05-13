<?php

namespace App\Http\Controllers;

use App\Events\Complaints;
use App\Http\Requests\StoreComplaintRequest;
use App\Models\Complaint;
use App\Models\ComplaintFile;
use App\Models\Task;
use App\Repositories\UserRepository;
use App\Services\FileUploaderService;
use App\Services\SendEmailService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ComplaintController extends Controller
{
	private $userRepository, $fileUploader, $sendEmail;

	public function __construct(FileUploaderService $fileUploader, SendEmailService $sendEmail, UserRepository $userRepository)
	{
		$this->fileUploader = $fileUploader;
		$this->sendEmail = $sendEmail;
		$this->userRepository = $userRepository;
	}

    public function store(StoreComplaintRequest $request)
	{
		$complaint = Complaint::create([
			'description' => $request['description'],
			'phone_contact' => !!$request['phone'] ? $request['phone'] : 0,
			'email_contact' => $request['email'],
			'asigned_to_id' => $request['user_id']
		]);

		$this->sendEmail->complaintConfirm($request['description'], $request['email'], );

		if (!!$request['files']) {
			foreach ($request['files'] as $file) {
				$file = $this->fileUploader->upload($file, [
							'type' => 'Complaints',
							'id' => $complaint->id
						]);

				$this->storeComplaintFile($complaint->id, $file['filename']);
			}
		} else {
			false;
		}

		// event(new Complaints(true));

		return response()->json($complaint);
	}

	public function storeComplaintFile(int $task_id, String $filename)
	{
		ComplaintFile::create([
			'complaint_id' => $task_id,
			'file' => $filename
		]);
	}

	public function getAdminComplaints(request $request)
	{
		$brands = $this->userRepository->getBrandsByEnvironment($request->current_environment);

		$admin_complaints = Complaint::with(['user.brand', 'files', 'tasks' => function ($q){
									return $q->where('parent_id', 0)->with(['thread.files']);
								}])
								->whereHas('user', function ($q) use ($brands) {
									return $q->whereIn('cat_brand_id', $brands);
								})
								->where('deleted', 0)
								->get();

		$admin_tasks = Task::where('parent_id', 0)
						->whereIn('complaint_id', $admin_complaints->pluck('id'))->get();

		return response()->json([
			'admin_complaints' => $admin_complaints,
			'admin_tasks' => $admin_tasks
		]);
	}

	public function changeStatus(Request $request)
	{
		$complaint = Complaint::where('id', $request->id)
							->firstOrFail();

		$complaint->status = true;
		$complaint->save();

		return response()->json([
			'complaint' => $complaint
		]);
	}
}
