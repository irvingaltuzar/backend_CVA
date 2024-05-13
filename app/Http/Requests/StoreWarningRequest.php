<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreWarningRequest extends FormRequest
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
            'cat_warning_type_id' => 'required',
			'date' => 'date|required',
			'expiration_date' => 'date|required',
			'user_id' => 'required',
			'brand_id' => 'required',
			'title' => 'required',
			'description' => 'required',
			'amount' => 'required',
        ];
    }

	public function attributes()
	{
        return [
            'cat_warning_type_id' => 'tipo de amonestacion',
			'date' => 'fecha',
			'expiration_date' => 'fecha de vencimiento',
			'user_id' => 'usuario',
			'brand_id' => 'marca',
			'title' => 'titulo',
			'description' => 'descripcion',
			'amount' => 'cantidad'
        ];
	}
}
