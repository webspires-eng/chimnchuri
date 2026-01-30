<?php

namespace App\Http\Requests\Api\V1\Admin;

use Illuminate\Support\Facades\DB;

class ItemSizeUpdateRequest extends ApiFormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            
            "item_id" => "required|integer|exists:items,id",
            "name" => [
                "required",
                "string",
                function ($attribute, $value, $fail) {
                    $itemId = $this->input('item_id');
                    $currentId = $this->route('size') ?? $this->input('id');

                    if (!$itemId || !$value) {
                        return;
                    }

                    $query = DB::table('item_sizes')
                        ->where('item_id', $itemId)
                        ->where('name', $value);

                    if ($currentId) {
                        $query->where('id', '<>', $currentId);
                    }

                    if ($query->exists()) {
                        $fail('This size name already exists for the selected item.');
                    }
                },
            ],

            'image' => 'nullable|image',
            "price" => "required|numeric|min:0",
            "discount" => "nullable|numeric|min:0",
            "discount_type" => "nullable|in:fixed,percentage",
        ];
    }


   

}
