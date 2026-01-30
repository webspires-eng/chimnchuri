<?php

namespace App\Http\Requests\Api\V1\Admin;


class AddonItemUpdateRequest extends ApiFormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $addonItemId = $this->route("addon_item");
        return [
            "name" => "required|string|max:225",
            "slug" => "nullable|string|unique:addon_items,slug," . $addonItemId,
            "description" => "nullable|string",
            "image" => "nullable|image|max:1024",
            "price" => "required"
        ];
    }


}
