<?php

namespace App\Http\Requests\Api\V1\Admin;

class AddonItemStoreRequest extends ApiFormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "name" => "required|string|max:225",
            "slug" => "nullable|string|unique:addon_items,slug",
            "description" => "nullable|string",
            "image" => "nullable|image|max:1024",
            "price" => "required"
        ];
    }
}
