<?php

namespace App\Services\Api\V1\Admin;

use App\Models\ItemSize;
use App\Repositories\Api\V1\Admin\ItemSizeRepository;
use Illuminate\Support\Str;

class ItemSizeService
{

    protected ItemSizeRepository $itemSizeRepository;

    /**
     * Create a new class instance.
     */
    public function __construct(ItemSizeRepository $itemSizeRepository)
    {
        $this->itemSizeRepository = $itemSizeRepository;
    }



    public function store(array $data)
    {

        
        return $this->itemSizeRepository->store($data);
    }


    public function getById($id)
    {
        return $this->itemSizeRepository->getById($id);
    }

    public function update($id, array $data)
    {
        return $this->itemSizeRepository->update($id, $data);
    }


    public function delete($id)
    {
        return $this->itemSizeRepository->delete($id);
    }


}
