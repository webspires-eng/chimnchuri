@extends('admin.layouts.app')

@section('content')
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <form method="POST" action="{{ route('payment-gateways.update', $gateway->id) }}">
        @csrf
        @method('PUT')
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Update Payment Gateway</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input class="form-control mb-2" name="name" value="{{ $gateway->name }}" placeholder="Name">
                        </div>
                    </div>
                    {{-- <div class="col-lg-6">
                        <div class="mb-3">
                            <label for="code" class="form-label">Code</label>
                            <input class="form-control mb-2" name="code" value="{{ $gateway->code }}"
                                placeholder="Code (stripe)">
                        </div>
                    </div> --}}

                    <div class="col-lg-6">
                        <div class="mb-3">
                            <label for="driver_class" class="form-label">Driver Class</label>
                            <input class="form-control mb-2" name="driver_class" value="{{ $gateway->driver_class }}"
                                placeholder="Driver Class">

                        </div>
                    </div>

                </div>

                @if ($gateway->code == 'stripe')
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label for="name" class="form-label">Publishable Key</label>
                                <input class="form-control mb-2" name="publishable_key" placeholder="Publishable Key"
                                    value="{{ $gateway->setting->config['publishable_key'] ?? '' }}">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label for="name" class="form-label">Secret Key</label>
                                <input class="form-control mb-2" name="secret_key" placeholder="Secret Key"
                                    value="{{ $gateway->setting->config['secret_key'] ?? '' }}">
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label for="name" class="form-label">Webhook Secret</label>
                                <input class="form-control mb-2" name="webhook_secret" placeholder="Webhook Secret"
                                    value="{{ $gateway->setting->config['webhook_secret'] ?? '' }}">
                            </div>
                        </div>

                    </div>
                @endif


                @if ($gateway->code == 'paypal')
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label for="name" class="form-label">Client ID</label>
                                <input class="form-control mb-2" name="client_id" placeholder="Client ID"
                                    value="{{ $gateway->setting->config['client_id'] ?? '' }}">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label for="name" class="form-label">Secret Key</label>
                                <input class="form-control mb-2" name="secret_key" placeholder="Secret Key"
                                    value="{{ $gateway->setting->config['secret_key'] ?? '' }}">
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label for="name" class="form-label">Webhook Secret</label>
                                <input class="form-control mb-2" name="webhook_secret" placeholder="Webhook Secret"
                                    value="{{ $gateway->setting->config['webhook_secret'] ?? '' }}">
                            </div>
                        </div>

                    </div>
                @endif

                <div class="row">
                    <div class="col-lg-6">
                        <div class="mb-3">
                            <select name="currency" class="form-select mb-2">
                                <option value="GBP">GBP</option>
                                <option value="USD">USD</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-6 ">
                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="hidden" name="is_enabled" id="is_enabled" value="0">
                            <input class="form-check-input" type="checkbox" name="is_enabled" id="is_enabled" value="1"
                                {{ $gateway->setting->is_enabled ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_enabled">Enable Gateway</label>
                        </div>
                    </div>
                </div>



            </div>
        </div>
        <div class="p-3 bg-light mb-3 rounded">
            <div class="row justify-content-end g-2">
                <div class="col-lg-2">
                    <button type="submit" class="btn btn-outline-secondary w-100">Update Payment Gateway</button>
                </div>
            </div>
        </div>
    </form>
@endsection
