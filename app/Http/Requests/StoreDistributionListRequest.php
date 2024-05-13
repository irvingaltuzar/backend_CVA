<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDistributionListRequest extends FormRequest
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
			'detail' => 'required',
			'description' => 'required',
			'cat_distribution_type' => 'required',
        ];
    }

	public function attributes()
	{
        return [
			'detail' => 'marcas',
			'description' => 'nombre de lista',
			'cat_distribution_type' => 'tipo de lista',
        ];
	}
}
