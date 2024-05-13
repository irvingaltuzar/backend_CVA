<?php

namespace App\Http\Controllers;

use App\Models\Complaint;
use App\Models\Task;
use App\Models\WorkPermit;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
	private $fileUploader, $userRepository;

	public function __construct(UserRepository $userRepository)
	{
		$this->userRepository = $userRepository;
	}

    public function getAdminStats()
	{
		$complaints = Complaint::where('deleted', 0)
						->where('parent_id', 0)
						->get();

		$tasks = Task::where('deleted', 0)->where('parent_id', 0)
					->get();
		$permits = 0;
		// $permits = WorkPermit::with(['user.userSec', 'type', 'files'])
		// 	->whereHas('boss', function ($query) {
		// 		$query->where('users_id',  1)->where('deleted', false);
		// 	})
		// 	->where('deleted', false)
		// 	->get();

		return response()->json([
			'dash_complaints' => $complaints,
			'dash_tasks' => $tasks,
			'dash_permits' => $permits
		]);
	}

	public function getUserStats()
	{
		$complaints = Complaint::with(['tasks' => function ($q) {
							return $q->where('parent_id', 0);
						}])
						->where('deleted', 1)
						->where('parent_id', 0)
						->where('asigned_to_id', $this->userRepository->getUser()->user[0]->id)
						->withCount(['tasks' => function ($query) {
							return $query->where('parent_id', 0);
						}])
						->get();

		$permits = WorkPermit::where('deleted', 0)
					->where('responsable_id', $this->userRepository->getUser()->user[0]->id)
					->where('parent_id', 0)
					->get();

		return response()->json([
			'dash_complaints' => $complaints,
			'dash_tasks' => $complaints,
			'dash_permits' => $permits
		]);
	}
}
