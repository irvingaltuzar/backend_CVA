<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreWorkPermitRequest extends FormRequest
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
            'cat_work_permit_type_id' => 'required',
            'cat_brand_id' => 'required',
			'description' => 'required',
			'permit_date' => 'date|required',
			'end_date' => 'date|required',
			'warning_phone' => 'required',
        ];
    }

	public function attributes()
	{
		return [
			'cat_work_permit_type_id' => 'tipo de permiso',
			'cat_brand_id' => 'marca',
			'description' => 'descripción',
			'permit_date' => 'fecha de inicio',
			'end_date' => 'fecha de finalización',
			'warning_phone' => 'télefono de emergencia',
		];
	}
}
