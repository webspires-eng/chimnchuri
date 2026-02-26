<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DeliveryZone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DeliveryZoneController extends Controller
{
    /**
     * Display a listing of the delivery zones.
     */
    public function index()
    {
        $deliveryZones = DeliveryZone::orderBy('min_distance', 'asc')->paginate(10);
        return view('admin.delivery-zones.index', compact('deliveryZones'));
    }

    /**
     * Show the form for creating a new delivery zone.
     */
    public function create()
    {
        return view('admin.delivery-zones.create');
    }

    /**
     * Store a newly created delivery zone in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'nullable|string|max:255',
            'min_distance' => 'nullable|numeric|min:0',
            'max_distance' => 'nullable|numeric',
            'delivery_fee' => 'nullable|numeric|min:0',
            'minimum_order_amount' => 'nullable|numeric|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        DeliveryZone::create($request->all());

        return redirect()->route('admin.delivery-zones.index')->with('success', 'Delivery zone created successfully.');
    }

    /**
     * Show the form for editing the specified delivery zone.
     */
    public function edit(string $id)
    {
        $deliveryZone = DeliveryZone::findOrFail($id);
        return view('admin.delivery-zones.edit', compact('deliveryZone'));
    }

    /**
     * Update the specified delivery zone in storage.
     */
    public function update(Request $request, string $id)
    {
        $deliveryZone = DeliveryZone::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'nullable|string|max:255',
            'min_distance' => 'nullable|numeric|min:0',
            'max_distance' => 'nullable|numeric',
            'delivery_fee' => 'nullable|numeric|min:0',
            'minimum_order_amount' => 'nullable|numeric|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $deliveryZone->update($request->all());

        return redirect()->route('admin.delivery-zones.index')->with('success', 'Delivery zone updated successfully.');
    }

    /**
     * Remove the specified delivery zone from storage.
     */
    public function destroy(string $id)
    {
        $deliveryZone = DeliveryZone::findOrFail($id);
        $deliveryZone->delete();
        return redirect()->route('admin.delivery-zones.index')->with('success', 'Delivery zone deleted successfully.');
    }

    /**
     * Frontend API - Get all active delivery zones.
     */
    public function getDeliveryZones()
    {
        $zones = DeliveryZone::where('is_active', true)
            ->orderBy('min_distance', 'asc')
            ->get()
            ->map(function ($zone) {
                return [
                    'id' => $zone->id,
                    'name' => $zone->name,
                    'min_distance' => (float) $zone->min_distance,
                    'max_distance' => (float) $zone->max_distance,
                    'delivery_fee' => (float) $zone->delivery_fee,
                    'minimum_order_amount' => (float) $zone->minimum_order_amount,
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $zones,
        ]);
    }

    /**
     * Frontend API - Check delivery availability for a given distance.
     */
    public function checkDelivery(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'distance' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Please provide a valid distance.',
            ], 422);
        }

        $distance = $request->input('distance');
        $zone = DeliveryZone::getZoneForDistance($distance);

        if ($zone) {
            return response()->json([
                'success' => true,
                'can_deliver' => true,
                'data' => [
                    'zone_name' => $zone->name,
                    'delivery_fee' => (float) $zone->delivery_fee,
                    'minimum_order_amount' => (float) $zone->minimum_order_amount,
                    'distance' => (float) $distance,
                ],
            ]);
        }

        return response()->json([
            'success' => true,
            'can_deliver' => false,
            'message' => 'Sorry, we cannot deliver to your location. We only deliver within 3.5 miles.',
        ]);
    }
}
