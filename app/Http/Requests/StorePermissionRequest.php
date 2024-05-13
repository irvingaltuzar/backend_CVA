<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePermissionRequest extends FormRequest
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
			'user' => 'required',
			'sub_section_id' => 'required',
			'permissions' => 'required|array'
        ];
    }

	public function attributes()
	{
		return [
			'user' => 'usuario',
			'sub_section_id' => 'sub-sección',
			'permissions' => 'permisos'
		];
	}
}
