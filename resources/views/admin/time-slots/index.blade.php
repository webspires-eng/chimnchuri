@extends('admin.layouts.app')

@section('content')
    <div class="row">
        <div class="col-xl-12">

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

            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center gap-1">
                    <h4 class="card-title flex-grow-1">All Time Slots</h4>

                    <a href="{{ route('admin.time-slots.create') }}" class="btn btn-sm btn-primary">
                        Add Time Slot
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm align-middle mb-0 table-hover table-centered">
                            <thead class="bg-light-subtle">
                                <tr>
                                    <th style="width: 50px;">#ID</th>
                                    <th>Order Date</th>
                                    <th>Time</th>
                                    <th>Available</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($timeSlots as $timeSlot)
                                    <tr>
                                        <td>{{ $timeSlot->id }}</td>
                                        <td>
                                            @if ($timeSlot->orderDate)
                                                <span class="badge bg-info-subtle text-info">
                                                    {{ \Carbon\Carbon::parse($timeSlot->orderDate->date)->format('D j M') }}
                                                </span>
                                            @else
                                                <span class="badge bg-secondary-subtle text-secondary">No date</span>
                                            @endif
                                        </td>
                                        <td>
                                            {{ Carbon\Carbon::parse($timeSlot->start_time)->format('h:i A') }} -
                                            {{ Carbon\Carbon::parse($timeSlot->end_time)->format('h:i A') }}
                                        </td>
                                        <td>{{ $timeSlot->available_capacity }}</td>
                                        <td>
                                            @if ($timeSlot->is_active)
                                                <span class="badge bg-success-subtle text-success">Active</span>
                                            @else
                                                <span class="badge bg-danger-subtle text-danger">Inactive</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="d-flex gap-2">
                                                <a href="{{ route('admin.time-slots.edit', $timeSlot->id) }}"
                                                    class="btn btn-soft-primary btn-sm"><iconify-icon
                                                        icon="solar:pen-2-broken"
                                                        class="align-middle fs-18"></iconify-icon></a>
                                                <form action="{{ route('admin.time-slots.destroy', $timeSlot->id) }}"
                                                    method="POST"
                                                    onsubmit="return confirm('Are you sure you want to delete this time slot?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-soft-danger btn-sm">
                                                        <iconify-icon icon="solar:trash-bin-minimalistic-2-broken"
                                                            class="align-middle fs-18"></iconify-icon>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">No time slots found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">
                        <nav aria-label="Time slots pagination">
                            {{ $timeSlots->links() }}
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
