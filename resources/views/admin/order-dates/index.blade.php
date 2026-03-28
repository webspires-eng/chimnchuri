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
                    <h4 class="card-title flex-grow-1">Order Dates</h4>
                    <a href="{{ route('admin.order-dates.create') }}" class="btn btn-sm btn-primary">
                        Add Order Date
                    </a>
                </div>
                <div class="card-body">
                    <p class="text-muted mb-3">Manage which Fridays and Saturdays are available for ordering. You can set
                        dates as <strong>Open</strong>, <strong>Closed</strong>, or <strong>Sold Out</strong>.</p>
                    <div class="table-responsive">
                        <table class="table table-sm align-middle mb-0 table-hover table-centered">
                            <thead class="bg-light-subtle">
                                <tr>
                                    <th style="width: 50px;">#ID</th>
                                    <th>Date</th>
                                    <th>Day</th>
                                    <th>Time Slots</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($orderDates as $orderDate)
                                    <tr>
                                        <td>{{ $orderDate->id }}</td>
                                        <td>
                                            <strong>{{ \Carbon\Carbon::parse($orderDate->date)->format('j M Y') }}</strong>
                                        </td>
                                        <td>
                                            <span
                                                class="badge bg-info-subtle text-info">{{ ucfirst($orderDate->day_name) }}</span>
                                        </td>
                                        <td>
                                            <span
                                                class="badge bg-secondary-subtle text-secondary">{{ $orderDate->timeSlots->count() }}
                                                slots</span>
                                        </td>
                                        <td>
                                            <div class="dropdown">
                                                <button
                                                    class="btn btn-sm dropdown-toggle
                                                    @if ($orderDate->status == 'open') btn-success
                                                    @elseif($orderDate->status == 'sold_out') btn-warning
                                                    @else btn-danger @endif"
                                                    type="button" data-bs-toggle="dropdown">
                                                    @if ($orderDate->status == 'open')
                                                        Open
                                                    @elseif($orderDate->status == 'sold_out')
                                                        Sold Out
                                                    @else
                                                        Closed
                                                    @endif
                                                </button>
                                                <ul class="dropdown-menu">
                                                    <li>
                                                        <form
                                                            action="{{ route('admin.order-dates.toggle-status', $orderDate->id) }}"
                                                            method="POST" class="d-inline">
                                                            @csrf
                                                            <input type="hidden" name="status" value="open">
                                                            <button type="submit"
                                                                class="dropdown-item {{ $orderDate->status == 'open' ? 'active' : '' }}">✅
                                                                Open</button>
                                                        </form>
                                                    </li>
                                                    <li>
                                                        <form
                                                            action="{{ route('admin.order-dates.toggle-status', $orderDate->id) }}"
                                                            method="POST" class="d-inline">
                                                            @csrf
                                                            <input type="hidden" name="status" value="sold_out">
                                                            <button type="submit"
                                                                class="dropdown-item {{ $orderDate->status == 'sold_out' ? 'active' : '' }}">🔶
                                                                Sold Out</button>
                                                        </form>
                                                    </li>
                                                    <li>
                                                        <form
                                                            action="{{ route('admin.order-dates.toggle-status', $orderDate->id) }}"
                                                            method="POST" class="d-inline">
                                                            @csrf
                                                            <input type="hidden" name="status" value="closed">
                                                            <button type="submit"
                                                                class="dropdown-item {{ $orderDate->status == 'closed' ? 'active' : '' }}">❌
                                                                Closed</button>
                                                        </form>
                                                    </li>
                                                </ul>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex gap-2">
                                                <a href="{{ route('admin.time-slots.create', ['order_date_id' => $orderDate->id]) }}"
                                                    class="btn btn-soft-success btn-sm" title="Add Time Slot"><iconify-icon
                                                        icon="solar:clock-circle-broken"
                                                        class="align-middle fs-18"></iconify-icon></a>
                                                <a href="{{ route('admin.order-dates.edit', $orderDate->id) }}"
                                                    class="btn btn-soft-primary btn-sm" title="Edit Date"><iconify-icon
                                                        icon="solar:pen-2-broken"
                                                        class="align-middle fs-18"></iconify-icon></a>
                                                <form action="{{ route('admin.order-dates.destroy', $orderDate->id) }}"
                                                    method="POST"
                                                    onsubmit="return confirm('Are you sure? This will also delete all time slots for this date.');">
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
                                        <td colspan="6" class="text-center">No order dates found. Create one to get
                                            started.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">
                        <nav aria-label="Order dates pagination">
                            {{ $orderDates->links() }}
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
