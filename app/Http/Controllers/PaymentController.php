<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\ItemSize;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderItemAddon;
use App\Models\Setting;
use App\Models\TimeSlot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Stripe\Stripe;
use Stripe\PaymentIntent;

class PaymentController extends Controller
{
    public function createPaymentIntent(Request $request)
    {
        Stripe::setApiKey(env('STRIPE_SECRET_KEY'));

        try {
            $paymentIntent = PaymentIntent::create([
                'amount' => 2000, // Amount in cents ($20.00)
                'currency' => 'usd',
                'automatic_payment_methods' => ['enabled' => true],
            ]);

            return response()->json([
                'clientSecret' => $paymentIntent->client_secret,
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }



    public function processCheckout(Request $request)
    {
        return Auth::user();
        // return response()->json($request->input());


        // dd($request->all());
        // 1. Validate the request (address, cart items, etc.)
        // $timeSlot = TimeSlot::find($request->time_slot_id);

        // $slotCapacity = $timeSlot->max_capacity; // 5
        // $alreadyBooked = Order::where('time_slot_id', $timeSlot->id)
        //     ->whereIn('order_status', ["pending", "accepted", "confirmed"])
        //     ->sum('steak_quantity');
        // $remainingCapacity = $slotCapacity - $alreadyBooked;

        // if (5 > $remainingCapacity) {
        //     return response()->json([
        //         'error' => "This slot only has $remainingCapacity steaks left."
        //     ]);
        // }


        // ✅ STEP 1: Count total steaks in THIS order
        // $steaksInOrder = collect($request->items)->sum('quantity');

        // // ✅ STEP 2: Check the 15-min slot capacity
        $timeSlot = TimeSlot::find($request->time_slot_id);

        // if (!$timeSlot) {
        //     return response()->json(['error' => 'Invalid time slot.'], 422);
        // }

        // // Count steaks ALREADY booked in this slot
        // $alreadyBookedInSlot = Order::where('time_slot_id', $timeSlot->id)
        //     ->whereIn('order_status', ['pending', 'accepted', 'confirmed'])
        //     ->sum('steak_quantity');  // total steaks, not orders

        // $remainingInSlot = $timeSlot->max_capacity - $alreadyBookedInSlot;

        // // ❌ Block if this order would exceed slot capacity
        // if ($steaksInOrder > $remainingInSlot) {
        //     return response()->json([
        //         'error' => "This time slot only has $remainingInSlot steak(s) left. Please choose another slot."
        //     ], 422);
        // }



        $setting = Setting::first();
        $subTotal = 0;
        $steakQty = 0;

        // 2. Create the Order in your DB
        $order = Order::create([
            'user_id' => Auth::user()->id ?? null,
            'uuid' => Str::uuid()->toString(),
            'order_number' => 'ORD-' . time(),

            'customer_name' => $request?->full_name ?? "Akif",
            'customer_phone' => $request?->phone ?? "0318978978",
            'customer_email' => $request?->email ?? "akifullah@gmail.com",
            'delivery_address' => $request?->street_address ?? "My addresss",
            'payment_method' => $request?->payment_method ?? "cod",
            'payment_status' => 'unpaid',
            'order_status' => 'pending',
            'steak_qty' => $steakQty,
            "time_slot_id" => $request->time_slot_id,
            "time_slot" => $timeSlot?->start_time . " - " . $timeSlot?->end_time,
        ]);

        foreach ($request->items as $cartItem) {

            $order_item =  OrderItem::create([
                'order_id' => $order->id,
                'item_id' => $cartItem["productId"],
                'size_id' => $cartItem["selectedSize"]["id"],

                'item_name' => $cartItem["name"],
                'size_name' => $cartItem["selectedSize"]["name"],

                'price' => $cartItem["selectedSize"]["price"],
                'quantity' => $cartItem["quantity"],
                'total' => $cartItem["itemTotal"]

            ]);
            $subTotal += ($order_item->price * $order_item->quantity);
            $steakQty += $order_item->quantity;

            if ($cartItem["selectedAddons"]) {
                foreach ($cartItem["selectedAddons"] as $addon) {
                    $subItem = OrderItemAddon::create([
                        'order_item_id' => $order_item->id,
                        'addon_id' => $addon["id"],
                        'category_name' => $addon["category"],
                        'name' => $addon["name"],
                        'price' => $addon["price"],
                        'quantity' => $addon["qty"],
                        'total' => $addon["price"] * ($addon["qty"] * $order_item->quantity)
                    ]);

                    $subTotal += ($subItem->price * ($subItem->quantity * $order_item->quantity));
                }
            }
        }


        $tax = $setting->tax_percentage > 0 ? $setting->tax_percentage : null;
        if ($tax) {
            $taxAmount = $subTotal * $setting->tax_percentage / 100;
        }

        if ($setting->delivery_charge) {
            $deliveryCharge = $setting->delivery_charge;
        }

        $grandTotal = $subTotal + $taxAmount + $deliveryCharge;

        $order->update([
            "tax_total" => $taxAmount ?? 0,
            "delivery_charges" => $deliveryCharge ?? 0,
            'sub_total' => $subTotal,
            "steak_qty" => $steakQty,
            'grand_total' => $grandTotal
        ]);

        if ($request?->payment_method == "online") {
            // 3. Create Stripe Payment Intent
            Stripe::setApiKey(env('STRIPE_SECRET_KEY'));

            $paymentIntent = PaymentIntent::create([
                'amount' => $grandTotal * 100,
                'currency' => $setting->currency_code ?? 'gbp',
                'capture_method' => 'manual',
                'automatic_payment_methods' => [
                    'enabled' => true,
                ],
                'metadata' => [
                    'order_id' => $order->id
                ]
            ]);

            $order->update([
                'payment_intent_id' => $paymentIntent->id,
            ]);

            return response()->json([
                "success" => true,
                "message" => "Order placed successfully",
                'clientSecret' => $paymentIntent->client_secret,
                'orderId' => $order->id
            ]);
        }

        return response()->json([
            "success" => true,
            "message" => "Order placed successfully",
            'orderId' => $order->id
        ]);
    }

    public function handleWebhook(Request $request)
    {
        Stripe::setApiKey(env('STRIPE_SECRET_KEY'));

        $payload = $request->getContent();
        $sig_header = $request->header('Stripe-Signature');
        $event = null;

        try {
            $event = \Stripe\Webhook::constructEvent(
                $payload,
                $sig_header,
                env('STRIPE_WEBHOOK_SECRET')
            );
        } catch (\UnexpectedValueException $e) {
            return response()->json(['error' => 'Invalid payload'], 400);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Invalid signature'], 400);
        }

        if ($event->type == 'charge.captured') {
            $paymentIntent = $event->data->object;
            $orderId = $paymentIntent->metadata->order_id;

            $order = Order::find($orderId);
            if ($order) {
                $order->update([
                    'payment_status' => 'paid',
                ]);
            }
        }

        if ($event->type == 'payment_intent.canceled') {
            $paymentIntent = $event->data->object;
            $orderId = $paymentIntent->metadata->order_id;

            $order = Order::find($orderId);
            if ($order) {
                $order->update([
                    'payment_status' => 'cancelled',
                ]);
            }
        }

        return response()->json(['success' => true]);
    }
}
