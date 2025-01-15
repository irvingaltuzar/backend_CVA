<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAnnouncementRequest;
use Illuminate\Support\Facades\Storage;
use App\Services\FileUploaderService;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Auth;
use App\Events\Announcements;
use App\Models\Announcement;
use App\Models\MessagesFile;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AnnouncementController extends Controller
{
	private $userRepository, $fileUploader;

	public function __construct(UserRepository $userRepository, FileUploaderService $fileUploader)
	{
		$this->userRepository = $userRepository;
		$this->fileUploader = $fileUploader;
	}

    public function store(StoreAnnouncementRequest $request)
	{
		$massive_announcement = collect();

		if (!!$request->massive) {
			foreach ($request['to'] as $user) {
				$announcement = Announcement::create([
					'to' => $user,
					'date' => $request['date'],
					'title' => $request['title'],
					'message' => $request['message'],
					'sended_by_id' => $this->userRepository->getUser()->user[0]->id,
					'is_massive' => true
				]);

				$announcement->load(['toName', 'conversation.sendedBy']);

				$massive_announcement->push($announcement);

				if (!!$request['files']) {
					foreach ($request['files'] as $file) {

						$file = $this->fileUploader->store($file, [
									'type' => 'Messages',
									'id' => $announcement->id
								]);

						$this->storeMessageFile($announcement->id, $file['filename']);
					}
				} else {
					false;
				}
			}

			// event(new Announcements($announcement));

			return response()->json($massive_announcement);

		} else {
			$announcement = Announcement::create([
				'to' => $request['to'],
				'date' => $request['date'],
				'title' => $request['title'],
				'message' => $request['message'],
				'sended_by_id' => $this->userRepository->getUser()->user[0]->id,
				'is_massive' => false
			]);

			if (!!$request['files']) {
				foreach ($request['files'] as $file) {

					$file = $this->fileUploader->store($file, [
								'type' => 'Messages',
								'id' => $announcement->id
							]);

					$this->storeMessageFile($announcement->id, $file['filename']);
				}
			} else {
				false;
			}

			// event(new Announcements($announcement));

			$announcement->load(['toName', 'conversation.sendedBy']);

			return response()->json($announcement);
		}
	}

	public function storeMessageFile(int $id, String $filename)
	{
		$uploaded = MessagesFile::create([
			'messages_id' => $id,
			'file' => $filename
		]);
	}

	public function getByUser()
	{
		$message = Announcement::whereIn('to', $this->userRepository->getUser()->user->pluck('id'))
					->whereNull('viewed_at')
					->where('deleted', false)
					->get();

		return response()->json($message);
	}

	public function getAdminData(int $type, int $current_environment)
	{
		$brands = $this->userRepository->getBrandsByEnvironment($current_environment);

		if ($type === 0) {
			$message = Announcement::with(['toName', 'conversation.sendedBy','files'])
						->whereHas('toName', function ($q) use ($brands){
							return $q->whereIn('cat_brand_id', $brands);
						})
						->where('deleted', false)
						->where('parent_id', false)
						->where('is_massive', false)
						->get();

			return response()->json($message);
		} else {
			try {
			$message = Announcement::with(['toName', 'conversation.sendedBy'])
						->whereHas('toName', function ($q) use ($brands){
							return $q->whereIn('cat_brand_id', $brands);
						})
						->where('deleted', false)
						->where('parent_id', false)
						->where('is_massive', true)
						->get()
						->groupBy([fn($announcement) => $announcement->title]);

			$arr =[];

			foreach ($message as $key => $value) {
				$temp_message['title']=$key;
				$temp_message['items']=$value;
				$arr[]=$temp_message;
			}

			return response()->json($arr);

			} catch (\Exception $e) {
				return response()->json(['error' => $e->getMessage()], 500);
			}
		}
	}

	public function getUserData()
	{
		$message = Announcement::with(['toName', 'conversation.sendedBy'])
					->whereIn('to', $this->userRepository->getUser()->user->pluck('id'))
					->where('parent_id', false)
					->where('deleted', false)
					->get();

		return response()->json($message);
	}

	public function getDetail(int $id)
	{

		$detail = Announcement::with(['toName', 'conversation.sendedBy', 'sendedBy'])->find($id);

		$this->updateMessage($id);

		$message_file = $this->getMessageFile($detail->id);

		if (!!$message_file) {
			$url = $this->getUrl($message_file);
			$hasFile = true;
		} else {
			$url = null;
			$hasFile = false;
		}

		return response()->json([
			'detail' => $detail,
			'url' => $url,
			'has_file' => $hasFile,
			'message_file' => $message_file
		]);
	}

	public function updateMessage(int $id)
	{
		Announcement::where('id', $id)->update([
			'viewed_at' => Carbon::now()
		]);
	}

	public function getUrl(MessagesFile $collect)
	{
		$url = Storage::disk('public')->url("Messages/{$collect->messages_id}/{$collect->file}");

		return $url;
	}

	public function getMessageFile(int $message_id)
	{
		$message_file = MessagesFile::where('messages_id', $message_id)->first();

		return $message_file;
	}

	public function sendMessage(Request $request)
	{
		$message = Announcement::create([
						'parent_id' => ($request->announcement['parent_id'] === 0) ? $request->announcement['id'] : $request->announcement['parent_id'],
						'to' => ($this->userRepository->getUser()->user[0]->id === $request->announcement['sended_by_id']) ? $request->announcement['to'] : $request->announcement['sended_by_id'],
						'date' => Carbon::now(),
						'title' => $request->announcement['title'],
						'message' => $request->message_text,
						'by_mail' => 0,
						'sended_by_id' => $this->userRepository->getUser()->user[0]->id,
						'deleted' => 0
					]);

		$message->load(['sendedBy']);

		// event(new Announcements($message));

		return response()->json($message);
	}
}
