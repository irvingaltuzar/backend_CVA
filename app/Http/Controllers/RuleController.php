<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRuleRequest;
use App\Models\BucketRuleEnvironment;
use App\Models\Environment;
use App\Models\Rule;
use App\Models\RuleFile;
use App\Repositories\UserRepository;
use App\Services\FileUploaderService;
use Illuminate\Http\Request;

class RuleController extends Controller
{
	private $userRepository, $fileUploader;

	public function __construct(UserRepository $userRepository, FileUploaderService $fileUploader)
	{
		$this->userRepository = $userRepository;
		$this->fileUploader = $fileUploader;
	}

	public function store(StoreRuleRequest $request)
	{
		$rule = Rule::create([
			'description' => $request['title'],
			'deleted' => false
		]);

		foreach ($request['files'] as $file) {

			$file = $this->fileUploader->store($file, [
						'type' => 'Rules',
						'id' => $rule->id
					]);

			$this->storeRuleFile($rule->id, $file['filename']);
		}

		$this->addBucketRule($rule->id, $request->environment_id);

		return response()->json($rule);
	}

	function addBucketRule(int $rule, int $environment_id) {
		BucketRuleEnvironment::create([
			'rule_id' => $rule,
			'environment_id' => $environment_id
		]);
	}

	public function storeRuleFile(int $id, String $filename)
	{
		$uploaded = RuleFile::create([
			'rule_id' => $id,
			'file' => $filename
		]);
	}

	function getData(Request $request) {

		$rules = Environment::with(['bucket_rule.rule'])->where('id', $request->current_environment)
					->paginate(10);

		return response()->json($rules);
	}

	function fetchRules(Request $request) {
		$rules = Environment::with(['bucket_rule.rule'])
					->where('id', $request->current_environment)
					->first();

		$rules = $rules->bucket_rule->groupBy([fn($rule) => $rule->rule->description]);

		return response()->json($rules);
	}
}
