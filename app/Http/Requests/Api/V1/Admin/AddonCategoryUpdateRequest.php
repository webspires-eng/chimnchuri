<?php

namespace App\Http\Requests\Api\v1\Admin;


class AddonCategoryUpdateRequest extends ApiFormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $id = $this->route("addon_category");
        return [
            "name" => "required|max:255|string|unique:addon_categories,name," . $id,
            "description" => "nullable|string",
            "image" => "nullable|image"
        ];
    }

}
