<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Admin\ItemSizeStoreRequest;
use App\Http\Requests\Api\V1\Admin\ItemSizeUpdateRequest;
use App\Services\Api\V1\Admin\ItemSizeService;
use Exception;

class ItemSizeController extends Controller
{

    protected ItemSizeService $itemSizeService;

    public function __construct(ItemSizeService $itemSizeService)
    {
        $this->itemSizeService = $itemSizeService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ItemSizeStoreRequest $request)
    {
        try {
            $size = $this->itemSizeService->store($request->all());
            return response()->json([
                "success" => true,
                "message" => "Size added successfully.",
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
            $size = $this->itemSizeService->getById($id);

            if (!$size) {
                return response()->json([
                    "success" => false,
                    "message" => "Size not found."
                ], 404);
            }

            return response()->json([
                "success" => true,
                "message" => "Size retrived successfully.",
                "data" => $size
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
    public function update(ItemSizeUpdateRequest $request, string $id)
    {
        try {

            $size = $this->itemSizeService->update($id, $request->all());

            if (!$size) {
                return response()->json([
                    "success" => false,
                    "message" => "The item is not found of this size."
                ], 404);
            }

            return response()->json([
                "success" => true,
                "message" => "Size updated successfully.",
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
            $size = $this->itemSizeService->delete($id);
            if (!$size) {
                return response()->json([
                    "success" => false,
                    "message" => "Size not found."
                ], 404);
            }

            return response()->json([
                "success" => true,
                "message" => "Size deleted successfully"
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
