<?php

namespace App\Http\Controllers;

use App\Events\Tasks;
use App\Http\Requests\StoreAdminTaskRequest;
use App\Http\Requests\StoreTaskRequest;
use App\Models\Complaint;
use App\Models\Task;
use App\Models\TaskFile;
use App\Repositories\UserRepository;
use App\Services\FileUploaderService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TaskController extends Controller
{
	private $fileUploader, $userRepository;

	public function __construct(FileUploaderService $fileUploader, UserRepository $userRepository)
	{
		$this->userRepository = $userRepository;
		$this->fileUploader = $fileUploader;
	}

    public function getAdminTasks()
	{
		$tasks = Task::with(['complaint.user', 'thread', 'files'])
					->whereHas('complaint', function ($query) {
						$query->whereIn('asigned_to_id', $this->userRepository->getUser()->user->pluck('id'));
					})
					->where('parent_id', false)
					->get();

		return response()->json($tasks);
	}

	public function store(StoreTaskRequest $request)
	{
		$task = Task::create([
			'complaint_id' => $request['complaint_id'],
			'parent_id' => 0,
			'description' => $request['description']
		]);

		if (!!$request['files']) {
			foreach ($request['files'] as $file) {

				$file = $this->fileUploader->upload($file, [
							'type' => 'Tasks',
							'id' => $task->id,
						]);

				$this->storeTaskFile($task->id, $file['filename']);
			}
		} else {
			false;
		}

		$task->load(['complaint.user']);

		return response()->json($task);
	}

	public function storeAdminTask(StoreAdminTaskRequest $request)
	{

		$complaint = Complaint::where('id', $request['complaint_id'])->update([
						'asigned_to_id' => $request['user_id']
					]);

		$task = Task::create([
			'complaint_id' => $request['complaint_id'],
			'parent_id' => 0,
			'description' => $request['description']
		]);

		if (!!$request['files']) {
			foreach ($request['files'] as $file) {

				$file = $this->fileUploader->upload($file, [
							'type' => 'Tasks',
							'id' => $task->id,
						]);

				$this->storeTaskFile($task->id, $file['filename']);
			}
		} else {
			false;
		}

		$task->load(['complaint.user']);

		return response()->json($task);
	}

	public function storeThread(StoreTaskRequest $request)
	{
		$task = Task::create([
			'complaint_id' => $request['complaint_id'],
			'parent_id' => $request->task_id,
			'description' => $request['description']
		]);

		event(new Tasks(false));

		if (!!$request['files']) {
			foreach ($request['files'] as $file) {

				$file = $this->fileUploader->upload($file, [
							'type' => 'Tasks',
							'id' => $task->id,
						]);

				$this->storeTaskFile($task->id, $file['filename']);
			}
		} else {
			false;
		}

		$task->load(['complaint.user']);

		return response()->json($task);
	}

	public function storeTaskFile(int $task_id, String $filename)
	{
		TaskFile::create([
			'task_id' => $task_id,
			'file' => $filename
		]);
	}

	public function changeStatus(Request $request)
	{
		$task = Task::where('id', $request->id)
							->firstOrFail();

		$task->status = true;
		$task->end_date = Carbon::now();
		$task->save();

		return response()->json([
			'task' => $task
		]);
	}
}
