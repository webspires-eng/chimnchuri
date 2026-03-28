@extends('admin.layouts.app')

@section('content')
    <form action="{{ route('admin.time-slots.store') }}" method="POST">
        @csrf
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Create Time Slot</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <input type="hidden" name="order_type" value="delivery">

                    <div class="col-lg-4">
                        <div class="mb-3">
                            <label for="order_date_id" class="form-label">Order Date</label>
                            <select id="order_date_id" name="order_date_id" class="form-select" required>
                                <option value="">Select a date</option>
                                @foreach ($orderDates as $orderDate)
                                    <option value="{{ $orderDate->id }}">
                                        {{ \Carbon\Carbon::parse($orderDate->date)->format('l, j M Y') }}
                                        ({{ ucfirst($orderDate->status) }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-lg-3">
                        <div class="mb-3">
                            <label for="start_time" class="form-label">Start Time</label>
                            <input type="time" id="start_time" name="start_time" class="form-control"
                                placeholder="Start Time" required>
                        </div>
                    </div>

                    <div class="col-lg-3">
                        <div class="mb-3">
                            <label for="end_time" class="form-label">End Time</label>
                            <input type="time" id="end_time" name="end_time" class="form-control" placeholder="End Time"
                                required>
                        </div>
                    </div>

                    <div class="col-lg-2">
                        <div class="mb-3">
                            <label for="available_capacity" class="form-label">Available Capacity</label>
                            <input type="number" id="available_capacity" name="available_capacity" class="form-control"
                                placeholder="Remaining Capacity" required value="5">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="p-3 bg-light mb-3 rounded">
            <div class="row justify-content-end g-2">
                <div class="col-lg-2">
                    <a href="{{ route('admin.time-slots.index') }}" class="btn btn-outline-secondary w-100">Cancel</a>
                </div>
                <div class="col-lg-2">
                    <button type="submit" class="btn btn-outline-success w-100">Create Time Slot</button>
                </div>
            </div>
        </div>
    </form>
@endsection
