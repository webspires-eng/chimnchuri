<?php

namespace App\Http\Requests\Api\V1\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;


class CategoryUpdateRequest extends ApiFormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $categoryId = $this->route('category') ?? null;
        return [
            "name" => "required|string|max:255",
            "slug" => "required|string|max:255|unique:categories,slug," . $categoryId,
            "description" => "nullable|string",
            "parent_id" => "nullable|exists:categories,id",
            "level" => "nullable|integer|min:0",
            "image" => "nullable|string",
            "sort_order" => "nullable|integer",
            "is_active" => "nullable|boolean",
            "is_featured" => "nullable|boolean"
        ];
    }


   
}
