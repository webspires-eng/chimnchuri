<?php

namespace App\Http\Requests\Api\v1\Admin;


class AddonCategoryStoreRequest extends ApiFormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "name" => "required|max:255|string|unique:addon_categories,name",
            "description" => "nullable|string",
            "image" => "nullable|image"
        ];
    }


}
