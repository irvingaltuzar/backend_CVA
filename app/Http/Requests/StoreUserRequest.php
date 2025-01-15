<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
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
			'nombre' => 'required',
			'apepa' => 'required',
			'apema' => 'required',
			'position' => 'required',
			'usuario' => 'required',
			'emails' => 'required',
			// 'birth_date' => 'required',
			// 'cat_brand_id' => 'required',
			'cat_user_type_id' => 'required'
        ];
    }

	public function attributes()
	{
		return [
			'responsable_first_name' => 'nombre',
			'apepa' => 'apellido paterno',
			'apema' => 'apellido materno',
			'position' => 'puesto',
			'emails' => 'dirección de correo',
			'cat_brand_id' => 'marca',
			'cat_user_type_id' => 'tipo de usuario',
		];
	}

	public function messages()
	{
		return  [
			'nombre' => 'El campo nombre es requerido',
			'apepa' => 'El campo apellido paterno es requerido',
			'apema' => 'El campo apellido materno es requerido',
			'birth_date' => 'El campo cumpleaños es requerido',
		];
	}
}
