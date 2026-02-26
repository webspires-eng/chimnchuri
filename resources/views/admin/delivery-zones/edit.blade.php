@extends('admin.layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <form action="{{ route('admin.delivery-zones.update', $deliveryZone->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Edit Delivery Zone</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Zone Name</label>
                                    <input type="text" id="name" name="name"
                                        class="form-control @error('name') is-invalid @enderror"
                                        placeholder="e.g. Zone 1 - Within 2.5 miles"
                                        value="{{ old('name', $deliveryZone->name) }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="is_active" class="form-label">Status</label>
                                    <select id="is_active" name="is_active"
                                        class="form-select @error('is_active') is-invalid @enderror" required>
                                        <option value="1"
                                            {{ old('is_active', $deliveryZone->is_active) == '1' ? 'selected' : '' }}>Active
                                        </option>
                                        <option value="0"
                                            {{ old('is_active', $deliveryZone->is_active) == '0' ? 'selected' : '' }}>
                                            Inactive</option>
                                    </select>
                                    @error('is_active')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            {{-- <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="min_distance" class="form-label">Min Distance (miles)</label>
                                    <input type="number" step="0.01" id="min_distance" name="min_distance"
                                        class="form-control @error('min_distance') is-invalid @enderror"
                                        placeholder="e.g. 0" value="{{ old('min_distance', $deliveryZone->min_distance) }}"
                                        required>
                                    @error('min_distance')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div> --}}
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="max_distance" class="form-label">Max Distance (miles)</label>
                                    <input type="number" step="0.01" id="max_distance" name="max_distance"
                                        class="form-control @error('max_distance') is-invalid @enderror"
                                        placeholder="e.g. 2.50"
                                        value="{{ old('max_distance', $deliveryZone->max_distance) }}" required>
                                    @error('max_distance')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="delivery_fee" class="form-label">Delivery Fee (&pound;)</label>
                                    <input type="number" step="0.01" id="delivery_fee" name="delivery_fee"
                                        class="form-control @error('delivery_fee') is-invalid @enderror"
                                        placeholder="e.g. 2.50"
                                        value="{{ old('delivery_fee', $deliveryZone->delivery_fee) }}" required>
                                    @error('delivery_fee')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">

                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="minimum_order_amount" class="form-label">Minimum Order Amount
                                        (&pound;)</label>
                                    <input type="number" step="0.01" id="minimum_order_amount"
                                        name="minimum_order_amount"
                                        class="form-control @error('minimum_order_amount') is-invalid @enderror"
                                        placeholder="e.g. 15.00"
                                        value="{{ old('minimum_order_amount', $deliveryZone->minimum_order_amount) }}"
                                        required>
                                    @error('minimum_order_amount')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="p-3 bg-light mb-3 rounded">
                    <div class="row justify-content-end g-2">
                        <div class="col-lg-2">
                            <button type="submit" class="btn btn-primary w-100">Update Delivery Zone</button>
                        </div>
                        <div class="col-lg-2">
                            <a href="{{ route('admin.delivery-zones.index') }}"
                                class="btn btn-outline-secondary w-100">Cancel</a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
