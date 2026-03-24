<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderDate;
use App\Models\OrderTimeSlot;
use App\Models\TimeSlot;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TimeSlotController extends Controller
{
    public function index()
    {
        $timeSlots = TimeSlot::with('orderDate')->orderBy('order_date_id', 'desc')->orderBy('start_time', 'asc')->paginate(50);
        return view('admin.time-slots.index', compact('timeSlots'));
    }

    public function create()
    {
        $orderDates = OrderDate::where('date', '>=', Carbon::today())->orderBy('date', 'asc')->get();
        return view('admin.time-slots.create', compact('orderDates'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'order_date_id' => 'required|exists:order_dates,id',
            'start_time' => 'required',
            'end_time' => 'required',
            'max_capacity' => 'required|integer|min:1',
        ]);

        TimeSlot::create([
            'order_type' => 'delivery',
            'order_date_id' => $request->order_date_id,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'max_capacity' => $request->max_capacity,
            'is_active' => true,
        ]);

        return redirect()->route('admin.time-slots.index')->with('success', 'Time slot created successfully!');
    }

    public function edit(TimeSlot $timeSlot)
    {
        $orderDates = OrderDate::where('date', '>=', Carbon::today())->orderBy('date', 'asc')->get();
        return view('admin.time-slots.edit', compact('timeSlot', 'orderDates'));
    }

    public function update(Request $request, TimeSlot $timeSlot)
    {
        $request->validate([
            'start_time' => 'required',
            'end_time' => 'required',
            'max_capacity' => 'required|integer|min:0',
        ]);

        $timeSlot->update([
            'order_type' => 'delivery',
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'max_capacity' => $request->max_capacity,
            'is_active' => $request->is_active,
        ]);

        return redirect()->route('admin.time-slots.index')->with('success', 'Time slot updated successfully!');
    }

    public function destroy(TimeSlot $timeSlot)
    {
        $timeSlot->delete();
        return redirect()->route('admin.time-slots.index')->with('success', 'Time slot deleted successfully!');
    }


    /**
     * API: Get time slots for a specific order date
     */
    public function getAllSlots(Request $request)
    {
        $orderDateId = $request->query('order_date_id');

        if (!$orderDateId) {
            return response()->json([
                "success" => true,
                "message" => "No order date selected.",
                "data" => []
            ], 200);
        }

        $orderDate = OrderDate::find($orderDateId);

        if (!$orderDate || $orderDate->status !== 'open') {
            return response()->json([
                "success" => true,
                "message" => "This date is not available for orders.",
                "data" => []
            ], 200);
        }

        // Get booked capacity for each slot on this date (exclude cancelled orders)
        $bookedSlots = OrderTimeSlot::whereHas('order', function ($q) use ($orderDate) {
            $q->where('order_date', $orderDate->date->format('Y-m-d'))
                ->where('order_status', '!=', 'cancelled');
        })->get();

        $query = TimeSlot::where("is_active", true)
            ->where("order_date_id", $orderDateId);

        $timeSlots = $query->get()->map(function ($timeSlot) use ($bookedSlots) {

            $slotStart = Carbon::parse($timeSlot->start_time);

            $timeSlot->start_time = $slotStart->format('g:i A');
            $timeSlot->end_time   = Carbon::parse($timeSlot->end_time)->format('g:i A');

            // Calculate booked capacity for this specific slot
            $bookedCapacity = $bookedSlots->where('time_slot_id', $timeSlot->id)->sum('capacity');

            $remainingCapacity = $timeSlot->max_capacity - $bookedCapacity;
            $timeSlot->max_capacity = max(0, $remainingCapacity);

            $timeSlot->disabled = $remainingCapacity <= 0;

            return $timeSlot;
        });

        return response()->json([
            "success" => true,
            "message" => "Retrieved time slots.",
            "data" => $timeSlots
        ], 200);
    }
}
