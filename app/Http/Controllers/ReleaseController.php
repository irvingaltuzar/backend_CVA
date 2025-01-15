<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEventRequest;
use App\Http\Requests\UpdateReleaseRequest;
use App\Models\BucketRelease;
use App\Models\Environment;
use App\Models\Release;
use App\Models\ReleaseFile;
use App\Services\FileUploaderService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReleaseController extends Controller
{
	private $fileUploader;

	public function __construct(FileUploaderService $fileUploader)
	{
			$this->fileUploader = $fileUploader;
	}

	function fetchData(Request $request)
	{
		$releases = Environment::with(['bucket_release' => function ($q) use ($request) {
							$q->with('release');
										if ($request->origin == 0) {
											$q->whereHas('release', function ($query) use ($request) {
												$query->where('start','<=',Carbon::now()->format('Y-m-d'))
														->where('end','>=',Carbon::now()->format('Y-m-d'));
											});
										} else {
											$q->with('release')
													->whereHas('release', function ($query) use ($request) {
														$query->where('has_calendar', $request->origin)
														->where('start','<=',Carbon::now()->format('Y-m-d'))
														->where('end','>=',Carbon::now()->format('Y-m-d'));
													});
										}
						}])
						->where('id', $request->current_environment)
						->first();

		return response()->json($releases);
	}

	function store(StoreEventRequest $request)
	{
		$release = Release::create([
			'title' => $request['title'],
			'start' => !!$request['start'] ? $request['start'] : Carbon::now(),
			'end' => !!$request['end'] ? $request['end'] : Carbon::now(),
			'color' => $request['color'],
			'link' => $request['link'],
			'has_calendar' => $request->hasCalendar,
			'description' => $request['description'],
		]);

		if (!!$request['files']) {
			foreach ($request['files'] as $file) {
				$file = $this->fileUploader->upload($file, [
							'type' => 'releases',
							'id' => $release->id
						]);

				$this->storeReleaseFile($release->id, $file['filename']);
			}
		} else {
			false;
		}

		$this->addBucketRelease($release->id, $request->environment_id);

		return response()->json($release);
	}

	function update(UpdateReleaseRequest $request)
	{
		$release = Release::where('id', $request->release_id)->update([
			'title' => $request['title'],
			'start' => $request['start'],
			'end' => $request['end'],
			'color' => $request['color'],
			'description' => $request['description'],
		]);

		if (!!$request['files']) {
			$this->deleteReleaseFile($request->release_id);

			foreach ($request['files'] as $file) {
				$file = $this->fileUploader->upload($file, [
							'type' => 'releases',
							'id' => $request->release_id
						]);

				$this->storeReleaseFile($request->release_id, $file['filename']);
			}
		} else {
			false;
		}
	}

	function delete(Request $request)
	{
		$release = Release::where('id', $request->eventId)->delete();

		return response()->json("Evento Eliminado correctamente", 200);
	}

	function deleteReleaseFile(int $release_id) {
		ReleaseFile::where('release_id', $release_id)->delete();
	}

	public function storeReleaseFile(int $release_id, String $filename)
	{
		ReleaseFile::create([
			'release_id' => $release_id,
			'file' => $filename
		]);
	}

	function addBucketRelease(int $release_id, int $environment_id) {
		BucketRelease::create([
			'release' => $release_id,
			'environment_id' => $environment_id
		]);
	}
}
