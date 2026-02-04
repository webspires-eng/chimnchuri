<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Voucher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class VoucherController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $vouchers = Voucher::orderBy("created_at", "desc")->paginate(10);
        return view("admin.vouchers.index", compact("vouchers"));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view("admin.vouchers.create");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "name" => "required",
            "code" => "required",
            "type" => "required",
            "discount_amount" => "required",
            "minimum_order_amount" => "nullable|numeric",
            "maximum_discount_amount" => "nullable|numeric",
            "start_date" => "required",
            "end_date" => "required",
            "is_active" => "required",
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $voucher = Voucher::create($request->all());

        return redirect()->route("vouchers.index")->with("success", "Voucher created successfully");
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
        $voucher = Voucher::find($id);
        return view("admin.vouchers.edit", compact("voucher"));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $voucher = Voucher::find($id);
        $voucher->delete();
        return redirect()->route("vouchers.index")->with("success", "Voucher deleted successfully");
    }
}
