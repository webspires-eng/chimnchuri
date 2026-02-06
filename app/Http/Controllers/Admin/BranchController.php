<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BranchController extends Controller
{
    public function index()
    {
        $branches = Branch::latest()->get();
        return view('admin.branches.index', compact('branches'));
    }

    public function create()
    {
        return view('admin.branches.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required',
            'address' => 'nullable',
            'phone' => 'nullable',
            'email' => 'nullable|email',
            'delivery_fee' => 'nullable|numeric',
            'min_order_amount' => 'nullable|numeric',
            'estimated_delivery_time' => 'nullable|integer',
            'tax_percentage' => 'nullable|numeric',
        ]);

        // $data['slug'] = Str::slug($request->name);

        Branch::create($request->all());

        return redirect()->route('admin.branches.index')
            ->with('success', 'Branch created successfully');
    }

    public function edit(Branch $branch)
    {
        return view('admin.branches.edit', compact('branch'));
    }

    public function update(Request $request, Branch $branch)
    {
        $data = $request->validate([
            'name' => 'required',
            'address' => 'nullable',
            'phone' => 'nullable',
            'email' => 'nullable|email',
            'delivery_fee' => 'nullable|numeric',
            'min_order_amount' => 'nullable|numeric',
            'estimated_delivery_time' => 'nullable|integer',
            'tax_percentage' => 'nullable|numeric',
            'is_active' => 'boolean'
        ]);

        // $data['slug'] = Str::slug($request->name);

        $branch->update($request->all());

        return redirect()->route('admin.branches.index')
            ->with('success', 'Branch updated successfully');
    }

    public function destroy(Branch $branch)
    {
        $branch->delete();

        return redirect()->route('admin.branches.index')
            ->with('success', 'Branch deleted successfully');
    }
}
