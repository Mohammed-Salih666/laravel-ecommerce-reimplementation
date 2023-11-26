<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAddressRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'user_id' => 'required|numeric', 
            'city' => 'required', 
            'district' => 'required', 
            'zipcode' => 'required|min:5', 
            'address' => 'nullable', 
            'is_default' => 'required|boolean'
        ];
    }

    public function messages()
    {
        return [
            'user_id.required' => 'The user id is required', 
            'city.required' => 'city is required', 
            'district.required' => 'district is required',
            'zipcode.required' => 'please enter the zip code', 
            'zipcode.min' => 'The minimum is 5 digits. The ZIP code is incorrect.', 
            'is_default' => 'This field is required'
        ];
    }
}
