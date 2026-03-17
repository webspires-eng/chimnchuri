<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\ItemSize;
use App\Models\Offer;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderItemAddon;
use App\Models\OrderTimeSlot;
use App\Models\Setting;
use App\Models\TimeSlot;
use Carbon\Carbon;
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
        // return Auth::user();

        // $timeSlot = TimeSlot::find($request?->time_slot_id);



        $setting = Setting::first();
        $subTotal = 0;
        $steakQty = 0;


        $pickupAddress = "";
        if ($request->order_type == "collection") {
            $pickupAddress = "{$setting?->address}, {$setting?->postcode}, {$setting?->city}";
        }


        // 2. Create the Order in your DB
        $order = Order::create([
            'user_id' => $request?->user_id ?? null,
            'uuid' => Str::uuid()->toString(),
            'order_number' => 'ORD-' . time(),

            'customer_name' => $request?->full_name ?? "NA",
            'customer_phone' => $request?->phone ?? "NA",
            'customer_email' => $request?->email ?? "NA",
            'delivery_address' => $request?->street_address ?? "NA",
            'payment_method' => $request?->payment_method ?? "cod",
            'payment_status' => 'unpaid',
            'order_status' => 'pending',
            'steak_qty' => $steakQty,

            "postal_code" => $request?->postal_code ?? null,
            "city" => $request?->city ?? null,
            "country" => $request?->country ?? null,

            "order_type" => $request?->order_type ?? "delivery",
            "order_date" => $request?->order_date ?? null,
            "delivery_time" => $request?->delivery_time ?? null,
            "pickup_time" => $request?->pickup_time ?? null,
            "pickup_address" => $pickupAddress,
            "order_instruction" => $request?->order_instruction ?? null,
            "car_registration" => $request?->car_registration ?? null,

            // "time_slot_id" => $request?->time_slot_id,
            // "time_slot" => Carbon::parse($timeSlot?->start_time)->format('h:i A') . " - " . Carbon::parse($timeSlot?->end_time)->format('h:i A'),
        ]);

        foreach ($request->items as $cartItem) {

            $sizeId = $cartItem["selectedSize"]["id"] ?? null;
            $itemId = $cartItem["productId"] ?? null;

            // Validate foreign keys exist to prevent constraint violations
            $sizeExists = $sizeId ? ItemSize::find($sizeId) : null;
            $itemExists = $itemId ? Item::find($itemId) : null;

            $order_item =  OrderItem::create([
                'order_id' => $order->id,
                'item_id' => $itemExists ? $itemId : null,
                'size_id' => $sizeExists ? $sizeId : null,

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


        $taxAmount = 0;
        $tax = $setting->tax_percentage > 0 ? $setting->tax_percentage : null;
        if ($tax) {
            $taxAmount = $subTotal * $setting->tax_percentage / 100;
        }

        $deliveryCharge = 0;
        if ($setting->delivery_charge && $request->order_type == "delivery") {
            $deliveryCharge = $setting->delivery_charge;
        }


        $offer = Offer::where("is_active", 1)->where("end_date", '>=', Carbon::now())->first();

        $discountAmount = 0;

        if ($offer && $offer->type == "percentage") {
            $discountAmount = min(($subTotal * $offer->value / 100), $offer->maximum_discount_amount);
        } else if ($offer && $offer->type == "fixed") {
            $discountAmount = min($offer->value, $offer->maximum_discount_amount);
        }


        $grandTotal = $subTotal + $taxAmount + $deliveryCharge - $discountAmount;

        $order->update([
            "tax_total" => $taxAmount ?? 0,
            "delivery_charges" => $deliveryCharge ?? 0,
            "discount_total" => $discountAmount ?? 0,
            'sub_total' => $subTotal,
            "steak_qty" => $steakQty,
            'grand_total' => $grandTotal
        ]);


        foreach ($request->allocations as $allocation) {
            $slotTime = TimeSlot::find($allocation['slot_id']);

            // if ($slotTime) {
            //     $slotTime->max_capacity = $slotTime->max_capacity - $allocation['quantity'];
            //     $slotTime->save();
            // }

            OrderTimeSlot::create([
                'order_id' => $order->id,
                'time_slot_id' => $slotTime->id,
                'start_time' => $slotTime->start_time,
                'end_time' => $slotTime->end_time,
                'capacity' => $allocation['quantity'],
            ]);
        }

        if ($request?->payment_method == "online") {
            // 3. Create Stripe Payment Intent — automatic capture (charges immediately)
            Stripe::setApiKey(env('STRIPE_SECRET_KEY'));

            $paymentIntent = PaymentIntent::create([
                'amount' => $grandTotal * 100,
                'currency' => $setting->currency_code ?? 'gbp',
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
                'paymentIntentId' => $paymentIntent->id,
                'orderId' => $order->id
            ]);
        }

        // COD orders — send admin email immediately
        try {
            \Illuminate\Support\Facades\Mail::to('akifullah0317@gmail.com')->send(new \App\Mail\AdminOrderPlaced($order));
            $order->update(['admin_email_sent_at' => now()]);
        } catch (\Exception $e) {
            logger()->error('Failed to send admin order notification email: ' . $e->getMessage());
        }

        return response()->json([
            "success" => true,
            "message" => "Order placed successfully",
            'orderId' => $order->id
        ]);
    }

    /**
     * Called by frontend after Stripe confirmCardPayment succeeds.
     * Verifies the payment status with Stripe and updates order accordingly.
     */
    public function verifyPayment(Request $request)
    {
        $request->validate([
            'order_id' => 'required|integer',
            'payment_intent_id' => 'required|string',
        ]);

        $order = Order::with(['items.addons', 'time_slots'])->find($request->order_id);

        if (!$order) {
            return response()->json(['success' => false, 'message' => 'Order not found'], 404);
        }

        // Already paid — skip
        if ($order->payment_status === 'paid') {
            return response()->json([
                'success' => true,
                'paid' => true,
                'message' => 'Payment already confirmed',
                'orderId' => $order->id,
            ]);
        }

        try {
            Stripe::setApiKey(env('STRIPE_SECRET_KEY'));
            $paymentIntent = PaymentIntent::retrieve($request->payment_intent_id);

            if ($paymentIntent->status === 'succeeded') {
                // Payment confirmed by Stripe — mark as paid IMMEDIATELY
                $order->update([
                    'payment_status' => 'paid',
                    'order_status' => 'confirmed',
                ]);

                // Send emails in background (non-blocking) so API responds instantly
                $orderId = $order->id;
                dispatch(function () use ($orderId) {
                    $order = Order::with(['items.addons', 'time_slots'])->find($orderId);
                    if (!$order) return;

                    // Send customer confirmation email
                    try {
                        if ($order->customer_email && !$order->customer_email_sent_at) {
                            \Illuminate\Support\Facades\Mail::to($order->customer_email)->send(new \App\Mail\OrderPlaced($order));
                            $order->update(['customer_email_sent_at' => now()]);
                        }
                    } catch (\Exception $e) {
                        logger()->error('Failed to send customer email: ' . $e->getMessage());
                    }

                    // Send admin notification email
                    try {
                        if (!$order->admin_email_sent_at) {
                            \Illuminate\Support\Facades\Mail::to('akifullah0317@gmail.com')->send(new \App\Mail\AdminOrderPlaced($order));
                            $order->update(['admin_email_sent_at' => now()]);
                        }
                    } catch (\Exception $e) {
                        logger()->error('Failed to send admin email: ' . $e->getMessage());
                    }
                })->afterResponse();

                return response()->json([
                    'success' => true,
                    'paid' => true,
                    'message' => 'Payment confirmed and order updated',
                    'orderId' => $order->id,
                ]);
            }

            // Payment not succeeded — keep unpaid, no emails
            return response()->json([
                'success' => true,
                'paid' => false,
                'message' => 'Payment not completed. Stripe status: ' . $paymentIntent->status,
                'orderId' => $order->id,
            ]);

        } catch (\Exception $e) {
            logger()->error('Verify payment failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'paid' => false,
                'message' => 'Failed to verify payment with Stripe',
            ], 500);
        }
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

            logger()->info('Stripe Webhook Event: ' . $event->type);
        } catch (\UnexpectedValueException $e) {
            return response()->json(['error' => 'Invalid payload'], 400);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Invalid signature'], 400);
        }

        // Handle successful payment (automatic capture)
        if ($event->type == 'payment_intent.succeeded') {
            $paymentIntent = $event->data->object;
            $orderId = $paymentIntent->metadata->order_id ?? null;

            if ($orderId) {
                $order = Order::with(['items.addons', 'time_slots'])->find($orderId);
                if ($order && $order->payment_status !== 'paid') {
                    $order->update([
                        'payment_status' => 'paid',
                        'order_status' => 'confirmed',
                    ]);
                }

                // Send emails in background
                if ($order) {
                    dispatch(function () use ($orderId) {
                        $order = Order::with(['items.addons', 'time_slots'])->find($orderId);
                        if (!$order) return;

                        try {
                            if ($order->customer_email && !$order->customer_email_sent_at) {
                                \Illuminate\Support\Facades\Mail::to($order->customer_email)->send(new \App\Mail\OrderPlaced($order));
                                $order->update(['customer_email_sent_at' => now()]);
                            }
                        } catch (\Exception $e) {
                            logger()->error('Failed to send customer email (webhook): ' . $e->getMessage());
                        }

                        try {
                            if (!$order->admin_email_sent_at) {
                                \Illuminate\Support\Facades\Mail::to('akifullah0317@gmail.com')->send(new \App\Mail\AdminOrderPlaced($order));
                                $order->update(['admin_email_sent_at' => now()]);
                            }
                        } catch (\Exception $e) {
                            logger()->error('Failed to send admin email (webhook): ' . $e->getMessage());
                        }
                    })->afterResponse();
                }
            }
        }

        // Handle manual capture (legacy fallback)
        if ($event->type == 'charge.captured') {
            $paymentIntent = $event->data->object;
            $orderId = $paymentIntent->metadata->order_id ?? null;

            if ($orderId) {
                $order = Order::with(['items.addons', 'time_slots'])->find($orderId);
                if ($order && $order->payment_status !== 'paid') {
                    $order->update([
                        'payment_status' => 'paid',
                        'order_status' => 'confirmed',
                    ]);

                    try {
                        if ($order->customer_email) {
                            \Illuminate\Support\Facades\Mail::to($order->customer_email)->send(new \App\Mail\OrderPlaced($order));
                            $order->update(['customer_email_sent_at' => now()]);
                        }
                    } catch (\Exception $e) {
                        logger()->error('Failed to send order confirmation email (webhook): ' . $e->getMessage());
                    }
                }
            }
        }

        if ($event->type == 'payment_intent.canceled') {
            $paymentIntent = $event->data->object;
            $orderId = $paymentIntent->metadata->order_id ?? null;

            if ($orderId) {
                $order = Order::find($orderId);
                if ($order) {
                    $order->update([
                        'payment_status' => 'cancelled',
                        'order_status' => 'cancelled',
                    ]);
                }
            }
        }

        return response()->json(['success' => true]);
    }
}
