<?php

namespace App\Services\Api\V1\Admin;

use App\Repositories\Api\V1\Admin\AddonGroupRepository;

class AddonGroupService
{

    private $addonGroupRepository;

    /**
     * Create a new class instance.
     */
    public function __construct(AddonGroupRepository $addonGroupRepository)
    {
        $this->addonGroupRepository = $addonGroupRepository;
    }
    public function listByItem(int $itemId)
    {
        return $this->addonGroupRepository->getByItem($itemId);
    }

    public function create(array $data)
    {
        return $this->addonGroupRepository->create($data);
    }

    public function update($id, $data)
    {
        return $this->addonGroupRepository->update($id, data: $data);
    }

}
