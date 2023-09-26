<?php

namespace App\Http\Requests\Api\job;

use App\Helpers\APIResponse;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class JobApiCreateFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(APIResponse::error('Validation failed', [
            'errors' => $validator->errors(),
        ], 422));
    }
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
            "title" => "required",
            "description" => "required",
            "country_id" => "required",
            "state_id" => "required",
            "city_id" => "required",
            "expiry_date" => "required"
        ];
    }


    public function messages()
    {
        return [
            'title.required' => __('title is required'),
            'description.required' => __('description is required'),
            'country_id.required' => __('country_id is required'),
            'state_id.required' => __('state_id is required'),
            'city_id.required' => __('city_id is required'),
            'expiry_date.required' => __('expiry_date is required'),

        ];
    }
}
