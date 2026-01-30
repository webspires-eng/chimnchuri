<?php

namespace App\Http\Requests\Api\V1\Admin;

use Illuminate\Support\Facades\DB;

class ItemSizeStoreRequest extends ApiFormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "sizes" => "nullable|array",
            "sizes.*.item_id" => "required|exists:items,id",
            "sizes.*.name" => [
                "nullable",
                function ($attribute, $value, $fail) {
                    $index = explode('.', $attribute)[1];
                    $itemId = $this->input("sizes.$index.item_id");

                    if (!$itemId || !$value) {
                        return;
                    }

                    $exists = DB::table('item_sizes')
                        ->where('item_id', $itemId)
                        ->where('name', $value)
                        ->exists();

                    if ($exists) {
                        $fail("This size name already exists for the selected item.");
                    }
                },
            ],

            'sizes.*.image' => 'nullable|image',
            "sizes.*.price" => "required|numeric|min:0",
            "sizes.*.discount" => "nullable|numeric|min:0",
            "sizes.*.discount_type" => "nullable|in:fixed,percentage",
        ];
    }


   
}
