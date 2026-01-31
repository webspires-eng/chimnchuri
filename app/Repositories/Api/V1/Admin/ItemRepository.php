<?php

namespace App\Repositories\Api\V1\Admin;

use App\Models\Item;

class ItemRepository
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }


    public function getAllItems()
    {
        $items = Item::orderBy("id", "desc")->get();
        return $items;
    }

    // STORE ITEM
    public function store(array $data)
    {
        return Item::create($data);
    }

    public function getItem($id)
    {
        $item = Item::find($id);

        if (!$item) {
            return false;
        }

        return $item;
    }

    public function updateItem($id, array $data)
    {
        $item = $this->getItem($id);

        if (!$item) {
            return false;
        }

        return $item->update($data);
    }

    // DELETE ITEM
    public function deleteItem($id)
    {
        $item = $this->getItem($id);
        if (!$item) {
            return false;
        }
        return $item->forceDelete();
    }
}
