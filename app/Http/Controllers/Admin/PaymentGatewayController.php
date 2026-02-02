<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentGateway;
use App\Models\PaymentGatewaySetting;
use Illuminate\Http\Request;

class PaymentGatewayController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $gateways = PaymentGateway::with('setting')->get();

        return view('admin.payment-gateways.index', compact('gateways'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.payment-gateways.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'code' => 'required|unique:payment_gateways',
            'driver_class' => 'required'
        ]);

        $gateway = PaymentGateway::create($request->all());

        PaymentGatewaySetting::create([
            'payment_gateway_id' => $gateway->id,
            'config' => [],
            'currency' => 'GBP',
            'is_enabled' => false
        ]);

        return redirect()->route('payment-gateways.index')
            ->with('success', 'Gateway Created Successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $gateway = PaymentGateway::with('setting')->findOrFail($id);

        return view('admin.payment-gateways.edit', compact('gateway'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $gateway = PaymentGateway::findOrFail($id);

        $gateway->update($request->only('name', 'driver_class', 'is_active'));

        $config = [];

        if ($gateway->code == 'stripe') {
            $config = [
                'publishable_key' => $request->publishable_key,
                'secret_key' => $request->secret_key,
                'webhook_secret' => $request->webhook_secret
            ];
        }

        if ($gateway->code == 'paypal') {
            $config = [
                'client_id' => $request->client_id,
                'client_secret' => $request->client_secret
            ];
        }

        $gateway->setting->update([
            'config' => $config,
            'currency' => $request->currency,
            'is_enabled' => $request->is_enabled
        ]);

        return redirect()->route('payment-gateways.index')
            ->with('success', 'Settings Updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        PaymentGateway::findOrFail($id)->delete();

        return back()->with('success', 'Gateway Deleted');
    }
}
