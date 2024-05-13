<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class SignatureUploaderService {

	public function upload(Array $payload)
	{
		$now = Carbon::now()->format('Y-m-d');

		$filename = "{$payload['user_name']}-{$now}";
		$extension = "jpg";
		$path = "signatures/{$payload['user_name']}/";

		$b64Image  = preg_replace('#^data:image/\w+;base64,#i', '', $payload['signature']);
		$imageFile = base64_decode($b64Image);

		Storage::disk('public')->put($path . "{$filename}.{$extension}", $imageFile);

		return [
			'filename' => $filename,
			'url' => Storage::disk('public')->url("signatures/{$payload['user_name']}/{$payload['user_name']}-{$now}.{$extension}")
		];
	}
}
