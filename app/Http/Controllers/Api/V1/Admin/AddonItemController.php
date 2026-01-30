<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Admin\AddonItemStoreRequest;
use App\Http\Requests\Api\V1\Admin\AddonItemUpdateRequest;
use App\Models\AddonItem;
use App\Services\Api\V1\Admin\AddonItemService;
use Exception;
use Illuminate\Http\Request;

class AddonItemController extends Controller
{
    private AddonItemService $addonItemService;

    public function __construct(AddonItemService $addonItemService)
    {
        $this->addonItemService = $addonItemService;
    }


    public function index()
    {
        try {

            $addons = $this->addonItemService->getAll();

            return response()->json([
                "success" => true,
                "message" => "All addon items have been retrieved",
                "data" => $addons
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
    public function store(AddonItemStoreRequest $request)
    {
        try {

            $addon = $this->addonItemService->create($request->all());

            return response()->json([
                "success" => true,
                "message" => "Addon item has been successfully created.",
                "data" => $addon
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
            $addon = $this->addonItemService->getById($id);
            if (!$addon) {
                return response()->json([
                    "success" => false,
                    "message" => "Addon item not found."
                ], 404);
            }

            return response()->json([
                "success" => true,
                "message" => "Addon item has been retrieved",
                "data" => $addon
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
    public function update(AddonItemUpdateRequest $request, string $id)
    {
        try {

            $addon = $this->addonItemService->update($id, $request->all());

            if (!$addon) {
                return response()->json([
                    "success" => false,
                    "message" => "Addon item not found."
                ], 404);
            }

            return response()->json([
                "success" => true,
                "message" => "Addon item has been successfully Updated.",
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
            $addon = $this->addonItemService->delete($id);
            if (!$addon) {
                return response()->json([
                    "success" => false,
                    "message" => "Addon item not found."
                ], 404);
            }

            return response()->json([
                "success" => true,
                "message" => "Addon item has been deleted",
                "data" => $addon
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
