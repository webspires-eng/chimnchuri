<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Admin\ItemStoreRequest;
use App\Http\Requests\Api\V1\Admin\ItemUpdateRequest;
use App\Services\Api\V1\Admin\ItemService;
use Exception;
use Illuminate\Http\Request;

class ItemController extends Controller
{

    public function __construct(
        protected ItemService $itemService
    ) {
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $items = $this->itemService->getAllItems();

            return response()->json([
                "success" => true,
                "message" => "All items are retrived",
                "data" => $items->load("sizes")
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                "success" => false,
                "message" => "Something went wrong.",
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ItemStoreRequest $request)
    {
        try {
            $item = $this->itemService->store($request->all());

            return response()->json([
                "success" => true,
                "message" => "Item created successfully.",
                "data" => $item
            ], 201);


        } catch (Exception $e) {
            logger()->error($e);
            return response()->json([
                'success' => false,
                'message' => 'Failed to create item.',
                // 'error' => app()->environment('production') ? null : $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $item = $this->itemService->getItem($id);

            if (!$item) {
                return response()->json([
                    "success" => false,
                    "message" => "Item not found."
                ], 404);
            }

            return response()->json([
                "success" => true,
                "message" => "Item retrived",
                "data" => $item->load("sizes")
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
    public function update(ItemUpdateRequest $request, string $id)
    {
        try {

            $item = $this->itemService->updateItem($id, $request->all());
            if (!$item) {
                return response()->json([
                    "success" => false,
                    "message" => "Item not found."
                ], 404);
            }

            return response()->json([
                "success" => true,
                "message" => "Item Updated",
                "data" => $item
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
            $item = $this->itemService->deleteItem($id);
            if (!$item) {
                return response()->json([
                    "success" => false,
                    "message" => "Item not found."
                ], 404);
            }

            return response()->json([
                "success" => true,
                "message" => "Item deleted",
                "data" => $item
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
