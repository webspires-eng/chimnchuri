<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\TimeSlot;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TimeSlotController extends Controller
{
    public function index()
    {
        $timeSlots = TimeSlot::paginate(20);
        return view('admin.time-slots.index', compact('timeSlots'));
    }

    public function create()
    {
        return view('admin.time-slots.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'start_time' => 'required',
            'end_time' => 'required',
            'max_capacity' => 'required|integer|min:1',
        ]);

        TimeSlot::create([
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'max_capacity' => $request->max_capacity,
            'is_active' => true,
        ]);

        return redirect()->route('admin.time-slots.index')->with('success', 'Time slots generated successfully!');
    }

    public function edit(TimeSlot $timeSlot)
    {
        return view('admin.time-slots.edit', compact('timeSlot'));
    }

    public function update(Request $request, TimeSlot $timeSlot)
    {
        $request->validate([
            'start_time' => 'required',
            'end_time' => 'required',
            'max_capacity' => 'required|integer|min:1',
        ]);

        $timeSlot->update([
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




    public function getAllSlots()
    {
        // $timeSlots = TimeSlot::get();
        // $timeSlots->map(function ($timeSlot) {
        //     $timeSlot->start_time = Carbon::parse($timeSlot->start_time)->format('g:i A');
        //     $timeSlot->end_time = Carbon::parse($timeSlot->end_time)->format('g:i A');
        // });

        $slotOrders = Order::with("items")->whereDate('created_at', Carbon::today())->get();

        $now = Carbon::now();

        $timeSlots = TimeSlot::where("is_active", true)->get()->map(function ($timeSlot) use ($now, $slotOrders) {

            $slotStart = Carbon::parse($timeSlot->start_time);

            $timeSlot->start_time = $slotStart->format('g:i A');
            $timeSlot->end_time   = Carbon::parse($timeSlot->end_time)->format('g:i A');

            $steak_qty = 0;
            foreach ($slotOrders as $slotOrder) {
                if ($slotOrder->time_slot_id == $timeSlot->id) {
                    $steak_qty += $slotOrder->steak_qty;
                }
            }

            // ✅ true if slot start time has already passed today
            $timeSlot->disabled = $steak_qty >= $timeSlot->max_capacity || $slotStart->lt($now);

            return $timeSlot;
        });
        return response()->json([
            "success" => true,
            "message" => "Retrived time slots.",
            "data" => $timeSlots
        ], 200);
    }
}
