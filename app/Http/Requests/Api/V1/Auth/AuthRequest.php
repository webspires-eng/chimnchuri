<?php

namespace App\Http\Requests\Api\V1\Auth;

use App\Http\Requests\Api\V1\Admin\ApiFormRequest;

class AuthRequest extends ApiFormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "email" => "required|email|string",
            "password" => "required"
        ];
    }

}
