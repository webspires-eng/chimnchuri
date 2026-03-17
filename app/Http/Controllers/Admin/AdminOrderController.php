<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\OrderTimelineService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Stripe\PaymentIntent;
use Stripe\Stripe;

class AdminOrderController extends Controller
{
    public function index()
    {
        $orders = Order::with("items", "user")->orderBy("created_at", "desc")->paginate(10);
        return view('admin.orders.list', compact('orders'));
    }

    public function show($id)
    {
        $order = Order::with("items.item.media", "items.addons", "time_slots", "user")->findOrFail($id);
        return view('admin.orders.detail', compact('order'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'order_status' => 'required|string'
        ]);

        $order = Order::findOrFail($id);
        $newStatus = $request->order_status;

        DB::beginTransaction();

        try {

            // Handle Online Payments
            if ($order->payment_method === 'online') {
                // Payment is captured automatically via Stripe — no manual capture needed
                if ($newStatus === 'cancelled') {
                    // Only refund/cancel if payment was already captured
                    try {
                        Stripe::setApiKey(config('services.stripe.secret'));
                        $paymentIntent = PaymentIntent::retrieve($order->payment_intent_id);
                        
                        if ($paymentIntent->status === 'requires_capture') {
                            $paymentIntent->cancel();
                        } elseif ($paymentIntent->status === 'succeeded') {
                            // Payment already captured — create a refund
                            \Stripe\Refund::create(['payment_intent' => $order->payment_intent_id]);
                        }
                    } catch (\Exception $e) {
                        logger()->error('Failed to cancel/refund Stripe payment: ' . $e->getMessage());
                    }
                    $order->payment_status = 'refunded';
                }
            }

            // Handle COD
            if ($order->payment_method === 'cod' && $newStatus === 'completed') {
                $order->payment_status = 'paid';
            }

            // Update Order Status once
            $order->order_status = $newStatus;
            $order->save();

            OrderTimelineService::add(
                $order->id,
                'Order Status Updated',
                'Order status updated to ' . $newStatus,
                $newStatus
            );

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            logger()->error('Order status update failed: ' . $e->getMessage());

            return back()->with('error', 'Something went wrong while updating order.');
        }

        // Send email outside transaction
        try {
            if ($order->customer_email) {
                // // Send status update email
                // Mail::to($order->customer_email)
                //     ->send(new \App\Mail\OrderStatusUpdated($order));

                // For COD orders: send the order confirmation email when admin confirms
                // Online payment orders get their confirmation email via Stripe webhook
                if ($newStatus === 'confirmed' && $order->payment_method === 'cod') {
                    $order->load(['items.addons', 'time_slots']);
                    Mail::to($order->customer_email)
                        ->send(new \App\Mail\OrderPlaced($order));
                    $order->update(['customer_email_sent_at' => now()]);
                }
            }
        } catch (\Exception $e) {
            logger()->error('Failed to send order email: ' . $e->getMessage());
        }

        return back()->with('success', 'Order status updated successfully');
    }


    public function printByDate(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
        ]);

        $date = Carbon::parse($request->date)->format('Y-m-d');

        $orders = Order::with('items.addons', 'time_slots', 'user')
            ->whereDate('order_date', $date)
            ->orderBy('created_at', 'asc')
            ->get();

        $printDate = Carbon::parse($date)->format('l, j F Y');

        return view('admin.orders.print', compact('orders', 'date', 'printDate'));
    }
    public function resendEmail(Request $request, $id)
    {
        $request->validate([
            'type' => 'required|in:customer,admin',
        ]);

        $order = Order::with('items.addons', 'time_slots')->findOrFail($id);

        // Set a shorter time limit for email sending
        set_time_limit(30);

        try {
            if ($request->type === 'customer') {
                if (!$order->customer_email) {
                    return back()->with('error', 'No customer email address on this order.');
                }
                Mail::to($order->customer_email)->send(new \App\Mail\OrderPlaced($order));
                $order->update(['customer_email_sent_at' => now()]);
                return back()->with('success', 'Customer confirmation email resent to ' . $order->customer_email);
            }

            if ($request->type === 'admin') {
                $adminEmail = config('mail.admin_email', 'order@chimnchurri.com');
                Mail::to($adminEmail)->send(new \App\Mail\AdminOrderPlaced($order));
                $order->update(['admin_email_sent_at' => now()]);
                return back()->with('success', 'Admin notification email resent to ' . $adminEmail);
            }
        } catch (\Exception $e) {
            logger()->error('Failed to resend email: ' . $e->getMessage());
            return back()->with('error', 'Failed to send email. Please check your SMTP settings in the admin panel. Error: ' . $e->getMessage());
        }

        return back()->with('error', 'Unknown email type.');
    }


    // public function updateStatus(Request $request, $id)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'order_status' => 'required'
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json([
    //             'status' => 'error',
    //             'message' => $validator->errors()->first(),
    //             "errors" => $validator->errors()
    //         ]);
    //     }

    //     $order = Order::findOrFail($id);

    //     if ($order->payment_method == "online" && $request->order_status == "confirmed") {

    //         Stripe::setApiKey(env('STRIPE_SECRET_KEY'));

    //         $paymentIntent = PaymentIntent::retrieve(
    //             $order->payment_intent_id
    //         );
    //         if ($paymentIntent->status == 'requires_capture') {
    //             $paymentIntent->capture();

    //             $order->update([
    //                 'order_status' => $request->order_status,
    //                 'payment_status' => 'pending'
    //             ]);
    //         }
    //     } else if ($order->payment_method == "online" && $request->order_status == "cancelled") {
    //         Stripe::setApiKey(env('STRIPE_SECRET_KEY'));

    //         $paymentIntent = PaymentIntent::retrieve(
    //             $order->payment_intent_id
    //         );

    //         if ($paymentIntent->status == "requires_capture") {
    //             $paymentIntent->cancel();
    //         }

    //         $order->update([
    //             'order_status' => $request->order_status,
    //             'payment_status' => 'unpaid'
    //         ]);
    //     }

    //     if ($order->payment_method == "cod" && $request->order_status == "completed") {
    //         $order->update([
    //             'order_status' => $request->order_status,
    //             'payment_status' => 'paid'
    //         ]);
    //     }

    //     $order->update([
    //         'order_status' => $request->order_status
    //     ]);
    //     OrderTimelineService::add(
    //         $order->id,
    //         'Order Status Updated',
    //         'Order status updated to ' . $request->order_status,
    //         $request->order_status
    //     );

    //     try {
    //         if ($order->customer_email) {
    //             \Illuminate\Support\Facades\Mail::to($order->customer_email)->send(new \App\Mail\OrderStatusUpdated($order));
    //         }
    //     } catch (\Exception $e) {
    //         logger()->error('Failed to send order status updated email: ' . $e->getMessage());
    //     }

    //     session()->flash('success', 'Order status updated successfully');
    //     return redirect()->back();
    //     // return response()->json([
    //     //     'status' => 'success',
    //     //     'message' => 'Order status updated successfully',
    //     //     'data' => $order
    //     // ]);
    // }
}
