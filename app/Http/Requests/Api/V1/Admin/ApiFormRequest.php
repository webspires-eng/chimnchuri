<?php

namespace App\Http\Requests\Api\V1\Admin;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

abstract class ApiFormRequest extends FormRequest
{

    public function authorize(): bool
    {
        return false;
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            "success" => false,
            "message" => "Request validation failed.",
            "errors" => $validator->errors()
        ], 422));
    }


    protected function failedAuthorization()
    {
        throw new HttpResponseException(response()->json([
            'success' => false,
            'message' => 'You are not authorized to perform this action.',
        ], 403));
    }
}
