@extends('admin.layouts.app')

@section('content')
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center gap-1">
                    <h4 class="card-title flex-grow-1">Delivery Zones</h4>

                    <a href="{{ route('admin.delivery-zones.create') }}" class="btn btn-sm btn-primary">
                        Add Delivery Zone
                    </a>
                </div>

                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show mx-3" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <div>
                    <div class="table-responsive">
                        <table class="table align-middle mb-0 table-hover table-centered">
                            <thead class="bg-light-subtle">
                                <tr>
                                    <th style="width: 20px;">#ID</th>
                                    <th>Zone Name</th>
                                    <th>Distance Range (Miles)</th>
                                    <th>Delivery Fee</th>
                                    <th>Min. Order</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($deliveryZones as $zone)
                                    <tr>
                                        <td>{{ $zone->id }}</td>
                                        <td>
                                            <span class="fw-medium fs-15">{{ $zone->name }}</span>
                                        </td>
                                        <td>
                                            <span class="badge bg-light text-dark fs-13">
                                                {{ $zone->min_distance }} - {{ $zone->max_distance }} miles
                                            </span>
                                        </td>
                                        <td>
                                            <span
                                                class="fw-semibold text-success">&pound;{{ number_format($zone->delivery_fee, 2) }}</span>
                                        </td>
                                        <td>
                                            <span
                                                class="fw-semibold">&pound;{{ number_format($zone->minimum_order_amount, 2) }}</span>
                                        </td>
                                        <td>
                                            @if ($zone->is_active)
                                                <span class="badge bg-success">Active</span>
                                            @else
                                                <span class="badge bg-danger">Inactive</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="d-flex gap-2">
                                                <a href="{{ route('admin.delivery-zones.edit', $zone->id) }}"
                                                    class="btn btn-soft-primary btn-sm">
                                                    <iconify-icon icon="solar:pen-2-broken"
                                                        class="align-middle fs-18"></iconify-icon>
                                                </a>
                                                <form action="{{ route('admin.delivery-zones.destroy', $zone->id) }}"
                                                    method="POST"
                                                    onsubmit="return confirm('Are you sure you want to delete this delivery zone?');">
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
                                        <td colspan="7" class="text-center">No delivery zones found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer border-top">
                    <nav aria-label="Page navigation example">
                        {{ $deliveryZones->links() }}
                    </nav>
                </div>
            </div>
        </div>
    </div>
@endsection
