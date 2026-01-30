<?php

namespace App\Services\Api\V1\Admin;

use App\Repositories\Api\V1\Admin\AddonCategoryRepository;
use Illuminate\Support\Str;

class AddonCategoryService
{

    protected $addonCategoryRepository;

    public function __construct(AddonCategoryRepository $addonCategoryRepository)
    {
        $this->addonCategoryRepository = $addonCategoryRepository;
    }

    // get all addon category
    public function getAll()
    {
        return $this->addonCategoryRepository->getAll();
    }

    // get by id
    public function getById($id)
    {
        return $this->addonCategoryRepository->getById($id);
    }

    // create 
    public function create(array $data)
    {

        $data["uuid"] = Str::uuid()->toString();
        
        return $this->addonCategoryRepository->create($data);
    }

    // UPDATE
    public function update($id, array $data)
    {
        return $this->addonCategoryRepository->update($id, $data);
    }

    // DELETE
    public function delete($id)
    {
        return $this->addonCategoryRepository->delete($id);
    }

}
