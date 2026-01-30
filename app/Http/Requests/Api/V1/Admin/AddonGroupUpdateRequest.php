<?php

namespace App\Http\Requests\Api\V1\Admin;

use Illuminate\Validation\Rule;

class AddonGroupUpdateRequest extends ApiFormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'item_id' => ['required', 'exists:items,id'],

            'addon_category_id' => [
                'required',
                'exists:addon_categories,id',
                Rule::unique('addon_groups')
                ->where(function ($q) {
                    return $q->where('item_id', $this->item_id);
                })
                ->ignore($this->route('id')),
            ],

            'selection_type' => ['required', 'in:single,multiple'],

            'min_qty' => ['nullable', 'integer', 'min:0'],
            'max_qty' => ['nullable', 'integer', 'min:1'],

            'is_required' => ['boolean'],
            'is_active' => ['boolean'],
            'sort_order' => ['nullable', 'integer'],

            'addon_items' => ['nullable', 'array'],

            'addon_items.*.addon_item_id' => [
                'required',
                'integer',
                'distinct',
                'exists:addon_items,id',
            ],

            'addon_items.*.price' => [
                'nullable',
                'numeric',
                'min:0',
            ],
        ];
    }

}
