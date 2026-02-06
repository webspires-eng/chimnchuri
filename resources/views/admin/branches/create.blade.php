        @extends('admin.layouts.app')

        @section('content')
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <form action="{{ route('admin.branches.store') }}" method="POST">
                @csrf
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">General Settings</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-4">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Branch Name</label>
                                    <input type="text" id="name" value="{{ old('name') }}" name="name"
                                        class="form-control" placeholder="Branch Name">
                                    @error('name')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="mb-3">
                                    <label for="phone" class="form-label">Phone</label>
                                    <input type="text" id="phone" value="{{ old('phone') }}" name="phone"
                                        class="form-control" placeholder="Phone">
                                    @error('phone')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" id="email" value="{{ old('email') }}" name="email"
                                        class="form-control" placeholder="Email">
                                    @error('email')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-lg-4">
                                <div class="mb-3">
                                    <label for="address" class="form-label">Address</label>
                                    <input type="text" id="address" value="{{ old('address') }}" name="address"
                                        class="form-control" placeholder="Address">
                                    @error('address')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-lg-4">
                                <div class="mb-3">
                                    <label for="currency_code" class="form-label">Currency</label>
                                    <input type="text" id="currency_code" value="{{ old('currency_code') }}"
                                        name="currency_code" class="form-control" placeholder="Currency">
                                    @error('currency_code')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-lg-4">
                                <div class="mb-3">
                                    <label for="currency_code" class="form-label">Currency Symbols</label>
                                    <input type="text" id="currency_symbol" value="{{ old('currency_symbol') }}"
                                        name="currency_symbol" class="form-control" placeholder="Currency Symbol">
                                    @error('currency_symbol')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-lg-4">
                                <div class="mb-3">
                                    <label for="tax_percentage" class="form-label">Tax Percentage (%)</label>
                                    <input type="number" step="0.01" id="tax_percentage"
                                        value="{{ old('tax_percentage', $settings->tax_percentage ?? '') }}"
                                        name="tax_percentage" class="form-control" placeholder="Tax Percentage">
                                    @error('tax_percentage')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-lg-4">
                                <div class="mb-3">
                                    <label for="delivery_fee" class="form-label">Delivery Charge</label>
                                    <input type="number" step="0.01" id="delivery_fee"
                                        value="{{ old('delivery_fee') }}" name="delivery_fee" class="form-control"
                                        placeholder="Delivery Charge">
                                    @error('delivery_fee')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-lg-4">
                                <div class="mb-3">
                                    <label for="min_order_amount" class="form-label">Minimum Order Amount</label>
                                    <input type="number" step="0.01" id="min_order_amount"
                                        value="{{ old('min_order_amount') }}" name="min_order_amount"
                                        class="form-control" placeholder="Minimum Order Amount">
                                    @error('min_order_amount')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-lg-4">
                                <div class="mb-3">
                                    <label for="estimated_delivery_time" class="form-label">Estimated Delivery Time
                                        (minutes)</label>
                                    <input type="number" id="estimated_delivery_time"
                                        value="{{ old('estimated_delivery_time') }}" name="estimated_delivery_time"
                                        class="form-control" placeholder="Estimated Delivery Time">
                                    @error('estimated_delivery_time')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-lg-4">
                                <div class="mb-3">
                                    <label for="estimated_pickup_time" class="form-label">Estimated Pickup Time
                                        (minutes)</label>
                                    <input type="number" id="estimated_pickup_time"
                                        value="{{ old('estimated_pickup_time') }}" name="estimated_pickup_time"
                                        class="form-control" placeholder="Estimated Pickup Time">
                                    @error('estimated_pickup_time')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-12">
                                <h4 class="text-start">Order Settings</h4>
                            </div>

                            <div class="col-lg-2">
                                <div class="mb-3">
                                    <label for="is_order_enabled"
                                        class="form-label d-block cursor-pointer user-select-none">Enable
                                        Order</label>
                                    <div class="form-check form-switch">
                                        <input type="hidden" name="is_order_enabled" value="0">
                                        <input class="form-check-input" type="checkbox" role="switch"
                                            id="is_order_enabled" name="is_order_enabled" value="1"
                                            {{ old('is_order_enabled') == 1 ? 'checked' : '' }}>
                                    </div>
                                    @error('is_order_enabled')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-lg-2">
                                <div class="mb-3">
                                    <label for="is_delivery_enabled"
                                        class="form-label d-block cursor-pointer user-select-none">Enable
                                        Delivery</label>
                                    <div class="form-check form-switch">
                                        <input type="hidden" name="is_delivery_enabled" value="0">
                                        <input class="form-check-input" type="checkbox" role="switch"
                                            id="is_delivery_enabled" name="is_delivery_enabled" value="1"
                                            {{ old('is_delivery_enabled') == 1 ? 'checked' : '' }}>
                                    </div>
                                    @error('is_delivery_enabled')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-lg-2">
                                <div class="mb-3">
                                    <label for="is_pickup_enabled"
                                        class="form-label d-block cursor-pointer user-select-none">Enable
                                        Pickup</label>
                                    <div class="form-check form-switch">
                                        <input type="hidden" name="is_pickup_enabled" value="0">
                                        <input class="form-check-input" type="checkbox" role="switch"
                                            id="is_pickup_enabled" name="is_pickup_enabled" value="1"
                                            {{ old('is_pickup_enabled') == 1 ? 'checked' : '' }}>
                                    </div>
                                    @error('is_pickup_enabled')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-lg-2">
                                <div class="mb-3">
                                    <label for="is_cod_enabled"
                                        class="form-label d-block cursor-pointer user-select-none">Enable
                                        COD</label>
                                    <div class="form-check form-switch">
                                        <input type="hidden" name="is_cod_enabled" value="0">
                                        <input class="form-check-input" type="checkbox" role="switch"
                                            id="is_cod_enabled" name="is_cod_enabled" value="1"
                                            {{ old('is_cod_enabled') == 1 ? 'checked' : '' }}>
                                    </div>
                                    @error('is_cod_enabled')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-lg-2">
                                <div class="mb-3">
                                    <label for="is_online_enabled"
                                        class="form-label d-block cursor-pointer user-select-none">Enable
                                        Online Payment</label>
                                    <div class="form-check form-switch">
                                        <input type="hidden" name="is_online_enabled" value="0">
                                        <input class="form-check-input" type="checkbox" role="switch"
                                            id="is_online_enabled" name="is_online_enabled" value="1"
                                            {{ old('is_online_enabled') == 1 ? 'checked' : '' }}>
                                    </div>
                                    @error('is_online_enabled')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                        </div>

                    </div>
                </div>
                </div>
                <div class="p-3 bg-light mb-3 rounded">
                    <div class="row justify-content-end g-2">
                        <div class="col-lg-2">
                            <button type="submit" class="btn btn-primary w-100">Save Branch</button>
                        </div>
                    </div>
                </div>
            </form>
        @endsection
