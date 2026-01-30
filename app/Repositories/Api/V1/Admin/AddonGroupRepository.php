<?php

namespace App\Repositories\Api\V1\Admin;

use App\Models\AddonGroup;
use Illuminate\Support\Facades\DB;

class AddonGroupRepository
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function getByItem(int $itemId)
    {
        return AddonGroup::with([
            'addonCategory',
            'addonItems.addonItem'
        ])
            ->where('item_id', $itemId)
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();
    }

    public function create(array $data)
    {
        return DB::transaction(function () use ($data) {
            $group = AddonGroup::create($data);
            if (isset($data['addon_items'])) {
                $group->addonItems()->delete();
                // dd($data["addon_items"]);
                $group->addonItems()->createMany($data["addon_items"]);
            }
            return $group;
        });
    }

    public function update($id, $data)
    {

        return DB::transaction(function () use ($id, $data) {
            $group = AddonGroup::find($id);
            if (!$group) {
                return false;
            }

            $group->update($data);
            if (isset($data["addon_items"])) {
                $group->addonItems()->forceDelete();
                $group->addonItems()->createMany($data["addon_items"]);
            }

            return $group;

        });
    }


}
