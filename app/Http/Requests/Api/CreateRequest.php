<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class CreateRequest extends FormRequest
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
            'startDate' => 'date|required',
            'endDate' => 'date|required',
            'name' => 'required',
            'brand' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'startDate.required' => 'The startDate is required. Format yy-mm-dd',
            'endDate.required' => 'The endDate is required. Format yy-mm-dd',
            'name.required' => 'The name field is required.',
            'brand.required' => 'The brand field is required.',
        ];
    }
}
