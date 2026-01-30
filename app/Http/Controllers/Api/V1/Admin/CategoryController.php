<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Admin\CategoryStoreRequest;
use App\Http\Requests\Api\V1\Admin\CategoryUpdateRequest;
use App\Services\Api\V1\Admin\CategoryService;
use Exception;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    protected CategoryService $service;
    public function __construct(CategoryService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        try {
            $categories = $this->service->getAllCategories();

            return response()->json([
                "success" => true,
                "message" => "All Categories are retrived.",
                "data" => $categories
            ], 200);

        } catch (Exception $e) {

            logger()->error($e);
            
            return response()->json([
                "success" => false,
                "message" => "Something went wrong.",
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CategoryStoreRequest $request)
    {
        try {
            $category = $this->service->create($request->all());
            return response()->json([
                "success" => true,
                "message" => "Category created successfully.",
                "data" => $category
            ], 200);

        } catch (Exception $e) {

            logger()->error($e);

            return response()->json([
                "success" => false,
                "message" => "Something went wrong.",
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $category = $this->service->getById($id);
            if (!$category) {
                return response()->json([
                    "success" => false,
                    "message" => "Category not found.",
                ], 404);
            }
            return response()->json([
                "success" => true,
                "message" => "Category retrived successfully.",
                "data" => $category
            ], 200);
        } catch (Exception $e) {

            logger()->error($e);

            return response()->json([
                "success" => false,
                "message" => "Something went wrong.",
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CategoryUpdateRequest $request, $id)
    {

        try {

            $category = $this->service->updateCategory($id, $request->validated());

            if (!$category) {
                return response()->json([
                    "success" => false,
                    "message" => "Category not found.",
                ], 404);
            }

            return response()->json([
                "success" => true,
                "message" => "Category updated successfully.",
            ], 200);

        } catch (Exception $e) {
            logger()->error($e);
            return response()->json([
                "success" => false,
                "message" => "Something goes wrong.",
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $category = $this->service->deleteById($id);
            if (!$category) {
                return response()->json([
                    "success" => false,
                    "message" => "Category not found.",
                ], 404);
            }

            return response()->json([
                "success" => true,
                "message" => "Category deleted successfully",
            ], 200);

        } catch (Exception $e) {
            logger()->error($e);
            return response()->json([
                "success" => false,
                "message" => "Something goes wrong.",
            ], 500);
        }
    }
}
