@extends('admin.layouts.app')

@section('content')
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="d-flex card-header justify-content-between align-items-center">
                    <div>
                        <h4 class="card-title">All Order List</h4>
                    </div>

                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table align-middle mb-0 table-hover table-centered">
                            <thead class="bg-light-subtle">
                                <tr>
                                    <th>Order ID</th>
                                    <th>Created at</th>
                                    <th>Customer</th>
                                    <th>Total</th>
                                    <th>Payment Status</th>
                                    <th>Items</th>
                                    <th>Delivery Number</th>
                                    <th>Order Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($orders as $order)
                                    <tr>
                                        <td>
                                            {{ $order->order_number }}
                                        </td>
                                        <td><span>{{ $order->created_at->format('d M, Y') }}</span>
                                            <br />
                                            <small>{{ $order->created_at->format('h:i A') }}</small>
                                        </td>
                                        <td>
                                            <a href="#!"
                                                class="link-primary fw-medium">{{ $order?->customer_name ?? 'Guest' }}</a>
                                        </td>
                                        <td> ${{ $order->grand_total }}</td>
                                        <td>
                                            @if ($order->payment_status == 'paid')
                                                <span class="badge bg-success text-light  px-2 py-1 fs-13">Paid</span>
                                            @else
                                                <span class="badge bg-warning   px-2 py-1 fs-13">Unpaid</span>
                                            @endif
                                        </td>
                                        <td> {{ $order->items->count() }}</td>
                                        <td> -</td>
                                        <td>

                                            @if ($order->order_status == 'draft')
                                                <span
                                                    class="badge border border-secondary text-secondary  px-2 py-1 fs-13">Draft</span>
                                            @elseif ($order->order_status == 'pending')
                                                <span
                                                    class="badge border border-warning text-warning  px-2 py-1 fs-13">Pending</span>
                                            @elseif($order->order_status == 'confirmed')
                                                <span
                                                    class="badge border border-info text-info  px-2 py-1 fs-13">Confirmed</span>
                                            @elseif ($order->order_status == 'processing')
                                                <span
                                                    class="badge border border-info text-info  px-2 py-1 fs-13">Processing</span>
                                            @elseif ($order->order_status == 'completed')
                                                <span
                                                    class="badge border border-success text-success  px-2 py-1 fs-13">Completed</span>
                                            @elseif ($order->order_status == 'cancelled')
                                                <span
                                                    class="badge border border-danger text-danger  px-2 py-1 fs-13">Cancelled</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="d-flex gap-2">
                                                <a href="{{ route('admin.orders.show', $order->id) }}"
                                                    class="btn btn-light btn-sm"><iconify-icon icon="solar:eye-broken"
                                                        class="align-middle fs-18"></iconify-icon></a>
                                                {{-- <a href="#!" class="btn btn-soft-primary btn-sm"><iconify-icon
                                                        icon="solar:pen-2-broken"
                                                        class="align-middle fs-18"></iconify-icon></a>
                                                <a href="#!" class="btn btn-soft-danger btn-sm"><iconify-icon
                                                        icon="solar:trash-bin-minimalistic-2-broken"
                                                        class="align-middle fs-18"></iconify-icon></a> --}}
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="10" class="text-center">
                                            No orders found
                                        </td>
                                    </tr>
                                @endforelse

                            </tbody>
                        </table>
                    </div>
                    <!-- end table-responsive -->
                </div>
                <div class="card-footer border-top">
                    <nav aria-label="Page navigation example">
                        {{ $orders->links() }}
                    </nav>
                </div>
            </div>
        </div>

    </div>
@endsection
