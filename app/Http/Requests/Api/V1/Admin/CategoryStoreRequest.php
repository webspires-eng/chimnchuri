<?php

namespace App\Http\Requests\Api\V1\Admin;


class CategoryStoreRequest extends ApiFormRequest
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
            "slug" => "required|string|max:255|unique:categories,slug",
            "description" => "nullable|string",
            "parent_id" => "nullable|exists:categories,id",
            "level" => "nullable|integer|min:0",
            "image" => "nullable|string",
            "sort_order" => "nullable|integer",
            "is_active" => "nullable|boolean",
            "is_featured" => "nullable|boolean"
        ];
    }

    public function messages(): array
    {
        return [
            "name.required" => "Category name is required.",
            "slug.unique" => "This slug name is already in use.",
        ];
    }

    
}
