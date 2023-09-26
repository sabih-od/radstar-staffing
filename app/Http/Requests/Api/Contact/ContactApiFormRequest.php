<?php

namespace App\Http\Requests\Api\Contact;

use App\Helpers\APIResponse;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class ContactApiFormRequest extends FormRequest
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
            "email" => "required|email",
            "message_txt" => "required"
        ];
    }

    public function messages()
    {
        return [
            'email.required' => __('Email is required'),
            'email.email' => __('The email must be a valid email address'),
            'message_txt.required' => __('Message is required'),
        ];
    }
}
