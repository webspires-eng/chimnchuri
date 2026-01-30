<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\v1\Admin\AddonCategoryStoreRequest;
use App\Http\Requests\Api\v1\Admin\AddonCategoryUpdateRequest;
use App\Services\Api\V1\Admin\AddonCategoryService;
use Exception;
use Illuminate\Http\Request;

class AddonCategoryController extends Controller
{

    protected AddonCategoryService $addonCategoryService;
    public function __construct(AddonCategoryService $addonCategoryService)
    {
        $this->addonCategoryService = $addonCategoryService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $category = $this->addonCategoryService->getAll();

            return response()->json([
                "success" => true,
                "message" => "Retrived all addon categories.",
                "data" => $category
            ], 200);

        } catch (Exception $e) {
            logger()->error($e);
            return response()->json([
                "success" => false,
                "message" => "Something went wrong."
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AddonCategoryStoreRequest $request)
    {

        try {

            $categroy = $this->addonCategoryService->create($request->all());

            return response()->json([
                "success" => true,
                "message" => "Addon category added successfully.",
                "data" => $categroy
            ], 201);


        } catch (Exception $e) {

            logger()->error($e);
            return response()->json([
                "success" => false,
                "message" => "Something went wrong."
            ], 500);

        }

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $addonCategory = $this->addonCategoryService->getById($id);
            if (!$addonCategory) {
                return response()->json([
                    "success" => false,
                    "message" => "Addon Category not found."
                ], 404);
            }

            return response()->json([
                "success" => true,
                "message" => "Retrived addon category.",
                "data" => $addonCategory
            ], 200);


        } catch (Exception $e) {
            logger()->error($e);
            return response()->json([
                "success" => false,
                "message" => "Something went wrong."
            ], 500);

        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(AddonCategoryUpdateRequest $request, string $id)
    {
        try {
            $addonCategory = $this->addonCategoryService->update($id, $request->all());
            if (!$addonCategory) {
                return response()->json([
                    "success" => false,
                    "message" => "Addon Category not found."
                ], 404);
            }

            return response()->json([
                "success" => true,
                "message" => "Addon category updated.",
            ], 200);
        } catch (Exception $e) {
            logger()->error($e);
            return response()->json([
                "success" => false,
                "message" => "Something went wrong."
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $addonCategory = $this->addonCategoryService->delete($id);
            if (!$addonCategory) {
                return response()->json([
                    "success" => false,
                    "message" => "Addon Category not found."
                ], 404);
            }

            return response()->json([
                "success" => true,
                "message" => "Addon category deleted.",

            ], 200);
        } catch (Exception $e) {
            logger()->error($e);
            return response()->json([
                "success" => false,
                "message" => "Something went wrong."
            ], 500);
        }

    }
}
