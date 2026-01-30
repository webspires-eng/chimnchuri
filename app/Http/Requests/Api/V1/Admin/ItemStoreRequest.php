<?php

namespace App\Http\Requests\Api\V1\Admin;


class ItemStoreRequest extends ApiFormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "name" => "required|string|max:255",
            "slug" => "required|string|unique:items,slug",
            "description" => "nullable|string",
            'image' => 'nullable|image',

            "sizes" => "nullable|array",
            "sizes.*.name" => "nullable",
            'sizes.*.image' => 'nullable|image',
            "sizes.*.price" => "required|numeric|min:0",
            "sizes.*.discount" => "nullable|numeric|min:0",
            "sizes.*.discount_type" => "nullable|in:fixed,percentage",

        ];
    }


 
    
}
