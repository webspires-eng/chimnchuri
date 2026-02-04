@extends('admin.layouts.app')

@section('content')
    <form action="{{ route('vouchers.store') }}" method="POST">
        @csrf
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Voucher Information</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-4">
                        <div class="mb-3">
                            <label for="name" class="form-label">Voucher Name</label>
                            <input type="text" id="name" value="{{ old('name') }}" name="name"
                                class="form-control" placeholder="Voucher Name" required>
                            @error('name')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="mb-3">
                            <label for="code" class="form-label">Code</label>
                            <input type="text" id="code" value="{{ old('code') }}" name="code"
                                class="form-control" placeholder="Code" required>
                            @error('code')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div class="mb-3">
                            <label for="type" class="form-label">Type</label>
                            <select name="type" id="type" class="form-select">
                                <option value="">Select Type</option>
                                <option value="percentage">Percentage</option>
                                <option value="fixed">Fixed</option>
                            </select>
                            @error('type')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div class="mb-3">
                            <label for="discount_amount" class="form-label">Discount Amount</label>
                            <input type="number" id="discount_amount" value="{{ old('discount_amount') }}"
                                name="discount_amount" class="form-control" placeholder="Discount Amount" required>
                            @error('discount_amount')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div class="mb-3">
                            <label for="minimum_order_amount" class="form-label">Minimum Order Amount</label>
                            <input type="number" id="minimum_order_amount" value="{{ old('minimum_order_amount') }}"
                                name="minimum_order_amount" class="form-control" placeholder="Minimum Order Amount">
                            @error('minimum_order_amount')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div class="mb-3">
                            <label for="maximum_discount_amount" class="form-label">Maximum Discount Amount</label>
                            <input type="number" id="maximum_discount_amount" value="{{ old('maximum_discount_amount') }}"
                                name="maximum_discount_amount" class="form-control" placeholder="Maximum Discount Amount">
                            @error('maximum_discount_amount')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div class="mb-3">
                            <label for="start_date" class="form-label">Start Date</label>
                            <input type="date" id="start_date" value="{{ old('start_date') }}" name="start_date"
                                class="form-control" placeholder="Start Date" required>
                            @error('start_date')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div class="mb-3">
                            <label for="end_date" class="form-label">End Date</label>
                            <input type="date" id="end_date" value="{{ old('end_date') }}" name="end_date"
                                class="form-control" placeholder="End Date" required>
                            @error('end_date')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div class="mb-3">
                            <label for="usage_limit" class="form-label">Usage Limit</label>
                            <input type="number" id="usage_limit" value="{{ old('usage_limit') }}" name="usage_limit"
                                class="form-control" placeholder="Usage Limit">
                            @error('usage_limit')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>


                    <div class="col-lg-4">
                        <div class="mb-3">
                            <label for="is_active" class="form-label">Is Active</label>
                            <select name="is_active" id="is_active" class="form-select">
                                <option value="">Select Is Active</option>
                                <option value="1">Yes</option>
                                <option value="0">No</option>
                            </select>
                            @error('is_active')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <div class="p-3 bg-light mb-3 rounded">
            <div class="row justify-content-end g-2">
                <div class="col-lg-2">
                    <button type="submit" class="btn btn-outline-secondary w-100">Create Voucher</button>
                </div>
            </div>
        </div>
    </form>
@endsection


@section('javascript')
@endsection
