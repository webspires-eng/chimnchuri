@extends('admin.layouts.app')

@section('content')
    <form action="{{ route('admin.time-slots.update', $timeSlot->id) }}" method="POST">
        @csrf
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Edit Time Slot</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <input type="hidden" name="order_type" value="delivery">

                    <div class="col-lg-4">
                        <div class="mb-3">
                            <label class="form-label">Order Date</label>
                            @if ($timeSlot->orderDate)
                                <input type="text" class="form-control"
                                    value="{{ \Carbon\Carbon::parse($timeSlot->orderDate->date)->format('l, j M Y') }}"
                                    disabled>
                            @else
                                <input type="text" class="form-control" value="No date assigned" disabled>
                            @endif
                        </div>
                    </div>

                    <div class="col-lg-3">
                        <div class="mb-3">
                            <label for="start_time" class="form-label">Start Time</label>
                            <input type="time" id="start_time" name="start_time" class="form-control"
                                placeholder="Start Time" required value="{{ $timeSlot->start_time }}">
                        </div>
                    </div>

                    <div class="col-lg-3">
                        <div class="mb-3">
                            <label for="end_time" class="form-label">End Time</label>
                            <input type="time" id="end_time" name="end_time" class="form-control" placeholder="End Time"
                                required value="{{ $timeSlot->end_time }}">
                        </div>
                    </div>

                    <div class="col-lg-2">
                        <div class="mb-3">
                            <label for="max_capacity" class="form-label">Max Capacity</label>
                            <input type="number" id="max_capacity" name="max_capacity" class="form-control"
                                placeholder="Max Capacity" required value="{{ $timeSlot->max_capacity }}">
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div class="mb-3">
                            <label for="is_active" class="form-label">Is Active</label>
                            <div class="form-check form-switch">
                                <input type="hidden" name="is_active" value="0">
                                <input class="form-check-input" type="checkbox" role="switch" id="is_active"
                                    name="is_active" value="1" {{ $timeSlot->is_active ? 'checked' : '' }}>
                            </div>
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
                    <button type="submit" class="btn btn-outline-success w-100">Update Time Slot</button>
                </div>
            </div>
        </div>
    </form>
@endsection
