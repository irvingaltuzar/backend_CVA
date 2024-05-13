<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreComplaintRequest extends FormRequest
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
			'user_id' => 'required|integer',
			'description' => 'required',
			'email' => 'required',
        ];
    }

	public function attributes()
	{
		return [
			'user_id' => 'marca',
			'description' => 'motivo de queja',
			'email' => 'correo de contacto',
		];
	}
}
