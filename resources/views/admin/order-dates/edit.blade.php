@extends('admin.layouts.app')

@section('content')
    {{-- Update Status Form --}}
    <form action="{{ route('admin.order-dates.update', $orderDate->id) }}" method="POST">
        @csrf
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Edit Order Date —
                    {{ \Carbon\Carbon::parse($orderDate->date)->format('l, j M Y') }}
                </h4>
            </div>
            <div class="card-body">

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="row">
                    <div class="col-lg-4">
                        <div class="mb-3">
                            <label for="date" class="form-label">Date</label>
                            <input type="date" id="date" name="date" class="form-control"
                                value="{{ \Carbon\Carbon::parse($orderDate->date)->format('Y-m-d') }}" required>
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select id="status" name="status" class="form-select" required>
                                <option value="open" {{ $orderDate->status == 'open' ? 'selected' : '' }}>Open</option>
                                <option value="closed" {{ $orderDate->status == 'closed' ? 'selected' : '' }}>Closed
                                </option>
                                <option value="sold_out" {{ $orderDate->status == 'sold_out' ? 'selected' : '' }}>Sold
                                    Out</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="p-3 bg-light mb-3 rounded">
            <div class="row justify-content-end g-2">
                <div class="col-lg-2">
                    <a href="{{ route('admin.order-dates.index') }}" class="btn btn-outline-secondary w-100">Cancel</a>
                </div>
                <div class="col-lg-2">
                    <button type="submit" class="btn btn-outline-success w-100">Update Order Date</button>
                </div>
            </div>
        </div>
    </form>

    {{-- Time Slots Table — kept outside the update form to avoid nested form conflicts --}}
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">Time Slots for this Date</h5>
            <a href="{{ route('admin.time-slots.create', ['order_date_id' => $orderDate->id]) }}" class="btn btn-sm btn-primary">Add Time Slot</a>
        </div>
        <div class="card-body">
            @if ($orderDate->timeSlots->count() > 0)
                <div class="table-responsive">
                    <table class="table table-sm table-bordered">
                        <thead class="bg-light-subtle">
                            <tr>
                                <th>Time</th>
                                <th>Capacity</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($orderDate->timeSlots->sortBy('start_time') as $slot)
                                <tr>
                                    <td>{{ \Carbon\Carbon::parse($slot->start_time)->format('g:i A') }} -
                                        {{ \Carbon\Carbon::parse($slot->end_time)->format('g:i A') }}</td>
                                    <td>{{ $slot->max_capacity }}</td>
                                    <td>
                                        @if ($slot->is_active)
                                            <span class="badge bg-success-subtle text-success">Active</span>
                                        @else
                                            <span class="badge bg-danger-subtle text-danger">Inactive</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            <a href="{{ route('admin.time-slots.edit', $slot->id) }}"
                                                class="btn btn-soft-primary btn-sm">
                                                <iconify-icon icon="solar:pen-2-broken"
                                                    class="align-middle fs-18"></iconify-icon>
                                            </a>
                                            <form action="{{ route('admin.time-slots.destroy', $slot->id) }}"
                                                method="POST" onsubmit="return confirm('Delete this time slot?');">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn btn-soft-danger btn-sm">
                                                    <iconify-icon icon="solar:trash-bin-minimalistic-2-broken"
                                                        class="align-middle fs-18"></iconify-icon>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-muted">No time slots for this date. <a href="{{ route('admin.time-slots.create') }}">Create
                        one</a></p>
            @endif
        </div>
    </div>
@endsection
