<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAdminTaskRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
			'complaint_id' => 'required',
			'user_id' => 'required',
			'description' => 'required',
        ];
    }

	public function attributes()
	{
		return [
			'complaint_id' => 'required',
			'user_id' => 'usuario',
			'description' => 'descripción',
		];
	}
}
