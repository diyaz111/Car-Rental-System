<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BookingCreateRequest extends FormRequest
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
            'user_id' => 'required|exists:users,id',
            'car_id' => 'required|exists:cars,id',
            'startdate' => 'required|date|after_or_equal:today',
            'enddate' => 'required|date|after:startdate',
            'total_price' => 'required|numeric|min:0',
        ];
    }

    public function messages()
    {
        return [
            'start_date.required' => 'The startDate is required. Format yy-mm-dd',
            'end_date.required' => 'The endDate is required. Format yy-mm-dd',
            'user_id.required' => 'The user_id field is required.',
            'total_price.required' => 'The total_price field is required.',
        ];
    }

    protected function prepareForValidation()
    {
        // Convert the total_price to an integer by removing any non-numeric characters
        if ($this->has('total_price')) {
            $this->merge([
                'total_price' => (int) filter_var($this->total_price, FILTER_SANITIZE_NUMBER_INT),
            ]);
        }
    }
}
