@extends('admin.layouts.app')

@section('content')
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <form method="POST" action="{{ route('payment-gateways.store') }}">
        @csrf
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Add Payment Gateway</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input class="form-control mb-2" name="name" placeholder="Name">
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="mb-3">
                            <label for="code" class="form-label">Code</label>
                            <input class="form-control mb-2" name="code" placeholder="Code (stripe)">
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="mb-3">
                            <label for="driver_class" class="form-label">Driver Class</label>
                            <input class="form-control mb-2" name="driver_class" placeholder="Driver Class">

                        </div>
                    </div>

                </div>

            </div>
        </div>
        <div class="p-3 bg-light mb-3 rounded">
            <div class="row justify-content-end g-2">
                <div class="col-lg-2">
                    <button type="submit" class="btn btn-outline-secondary w-100">Add Payment Gateway</button>
                </div>
            </div>
        </div>
    </form>
@endsection
