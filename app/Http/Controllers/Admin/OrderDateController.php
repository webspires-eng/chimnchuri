<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OrderDate;
use App\Models\TimeSlot;
use Carbon\Carbon;
use Illuminate\Http\Request;

class OrderDateController extends Controller
{
    public function index()
    {
        $orderDates = OrderDate::with('timeSlots')
            ->orderBy('date', 'asc')
            ->paginate(20);

        return view('admin.order-dates.index', compact('orderDates'));
    }

    public function create()
    {
        return view('admin.order-dates.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date|unique:order_dates,date',
            'status' => 'required|in:open,closed,sold_out',
        ]);

        $date = Carbon::parse($request->date);
        $dayName = strtolower($date->format('l')); // friday, saturday, etc.

        $orderDate = OrderDate::create([
            'date' => $request->date,
            'day_name' => $dayName,
            'status' => $request->status,
        ]);

        // Auto-generate time slots if requested
        if ($request->has('auto_generate') && $request->auto_generate) {
            $startTime = Carbon::parse($request->slot_start_time);
            $endTime = Carbon::parse($request->slot_end_time);
            $capacity = $request->slot_capacity ?? 5;

            while ($startTime->lt($endTime)) {
                $slotEnd = $startTime->copy()->addMinutes(15);
                if ($slotEnd->gt($endTime)) break;

                TimeSlot::create([
                    'order_date_id' => $orderDate->id,
                    'order_type' => 'delivery',
                    'start_time' => $startTime->format('H:i'),
                    'end_time' => $slotEnd->format('H:i'),
                    'max_capacity' => $capacity,
                    'is_active' => true,
                ]);

                $startTime->addMinutes(15);
            }
        }

        return redirect()->route('admin.order-dates.index')->with('success', 'Order date created successfully!');
    }

    public function edit(OrderDate $orderDate)
    {
        $orderDate->load('timeSlots');
        return view('admin.order-dates.edit', compact('orderDate'));
    }

    public function update(Request $request, OrderDate $orderDate)
    {
        $request->validate([
            'date' => 'required|date',
            'status' => 'required|in:open,closed,sold_out',
        ]);

        $orderDate->update([
            'date' => $request->date,
            'day_name' => \Carbon\Carbon::parse($request->date)->format('l'),
            'status' => $request->status,
        ]);

        return redirect()->route('admin.order-dates.index')->with('success', 'Order date updated successfully!');
    }

    public function destroy(OrderDate $orderDate)
    {
        $orderDate->delete(); // cascades to time_slots
        return redirect()->route('admin.order-dates.index')->with('success', 'Order date deleted successfully!');
    }

    /**
     * Quick toggle status via AJAX
     */
    public function toggleStatus(Request $request, OrderDate $orderDate)
    {
        $request->validate([
            'status' => 'required|in:open,closed,sold_out',
        ]);

        $orderDate->update(['status' => $request->status]);

        return redirect()->route('admin.order-dates.index')->with('success', 'Status updated to ' . ucfirst(str_replace('_', ' ', $request->status)));
    }

    /**
     * API: Get available order dates for frontend
     */
    public function getAvailableDates()
    {
        $dates = OrderDate::where('date', '>=', Carbon::today())
            ->orderBy('date', 'asc')
            ->get()
            ->map(function ($orderDate) {
                return [
                    'id' => $orderDate->id,
                    'date' => $orderDate->date->format('Y-m-d'),
                    'day_name' => ucfirst($orderDate->day_name),
                    'display_date' => $orderDate->date->format('l, j M Y'),
                    'short_date' => $orderDate->date->format('D j M'),
                    'status' => $orderDate->status,
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $dates,
        ]);
    }
}
