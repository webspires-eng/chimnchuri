<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Admin\AddonGroupStoreRequest;
use App\Http\Requests\Api\V1\Admin\AddonGroupUpdateRequest;
use App\Services\Api\V1\Admin\AddonGroupService;
use Exception;
use Illuminate\Http\Request;

class AddonGroupController extends Controller
{

    private $addonsGroupService;

    public function __construct(AddonGroupService $addonGroupService)
    {
        $this->addonsGroupService = $addonGroupService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index($itemId)
    {
        try {
            $addonGroup = $this->addonsGroupService->listByItem($itemId);
            if (!$addonGroup) {
                return response()->json([
                    "success" => false,
                    "message" => "Addon item not found."
                ], 404);
            }

            return response()->json([
                "success" => true,
                "message" => "Addon group retrived.",
                "data" => $addonGroup
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
    public function store(AddonGroupStoreRequest $request)
    {

        try {
            $addonGroup = $this->addonsGroupService->create($request->all());

            return response()->json([
                "success" => true,
                "message" => "Addon group created successfully.",
                "data" => $addonGroup
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(AddonGroupUpdateRequest $request, string $id)
    {


        try {
            $addonGroup = $this->addonsGroupService->update($id, $request->all());
            if (!$addonGroup) {
                return response()->json([
                    "success" => false,
                    "message" => "Addon group not found."
                ], 404);
            }

            return response()->json([
                "success" => true,
                "message" => "Addon group updated successfully.",
                "data" => $addonGroup
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
        //
    }
}
