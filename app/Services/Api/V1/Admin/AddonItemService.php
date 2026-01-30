<?php

namespace App\Services\Api\V1\Admin;

use App\Repositories\Api\V1\Admin\AddonItemRepository;
use Illuminate\Support\Str;

class AddonItemService
{

    private AddonItemRepository $addonItemRepository;
    public function __construct(AddonItemRepository $addonItemRepository)
    {
        $this->addonItemRepository = $addonItemRepository;
    }

    // GET ALL
    public function getAll()
    {
        return $this->addonItemRepository->getAll();
    }

    // GET BY ID
    public function getById($id)
    {
        return $this->addonItemRepository->getById($id);
    }

    // CREATE
    public function create(array $data)
    {
        $data["uuid"] = Str::uuid()->toString();
        return $this->addonItemRepository->create($data);
    }

    // UPDAPTE
    public function update($id, array $data)
    {
        return $this->addonItemRepository->update($id, $data);
    }


    // DELETE
    public function delete($id)
    {
        return $this->addonItemRepository->delete($id);
    }


}
