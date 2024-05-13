<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEventRequest extends FormRequest
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
            'title' => 'required',
			'description' => 'required',
			'start' => 'required_if:hasCalendar,true',
			'end' => 'required_if:hasCalendar,true',
			'files' => 'required',
			'color' => 'required'
        ];
    }

	public function attributes()
	{
		return [
			'title' => 'titulo',
			'description' => 'descripción',
			'start' => 'fecha de inicio',
			'end' => 'fecha de finalización',
		];
	}
}
