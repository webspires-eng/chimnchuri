@extends('admin.layouts.app')

@section('content')
    <form action="{{ route('admin.order-dates.store') }}" method="POST">
        @csrf
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Create Order Date</h4>
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
                            <input type="date" id="date" name="date" class="form-control" required
                                value="{{ old('date') }}">
                            <small class="text-muted">Select a Friday or Saturday</small>
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select id="status" name="status" class="form-select" required>
                                <option value="open" {{ old('status') == 'open' ? 'selected' : '' }}>Open</option>
                                <option value="closed" {{ old('status') == 'closed' ? 'selected' : '' }}>Closed</option>
                                <option value="sold_out" {{ old('status') == 'sold_out' ? 'selected' : '' }}>Sold Out
                                </option>
                            </select>
                        </div>
                    </div>
                </div>

                <hr>

                <h5 class="mb-3">Auto-Generate Time Slots (Optional)</h5>
                <p class="text-muted mb-3">Automatically create 15-minute interval time slots for this date.</p>

                <div class="row">
                    <div class="col-lg-2">
                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="auto_generate" name="auto_generate"
                                    value="1">
                                <label class="form-check-label" for="auto_generate">Auto-generate slots</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3" id="slot_fields" style="display: none;">
                        <div class="mb-3">
                            <label for="slot_start_time" class="form-label">Start Time</label>
                            <input type="time" id="slot_start_time" name="slot_start_time" class="form-control"
                                value="12:00">
                        </div>
                    </div>
                    <div class="col-lg-3" id="slot_fields_end" style="display: none;">
                        <div class="mb-3">
                            <label for="slot_end_time" class="form-label">End Time</label>
                            <input type="time" id="slot_end_time" name="slot_end_time" class="form-control"
                                value="18:00">
                        </div>
                    </div>
                    <div class="col-lg-2" id="slot_fields_capacity" style="display: none;">
                        <div class="mb-3">
                            <label for="slot_capacity" class="form-label">Capacity per Slot</label>
                            <input type="number" id="slot_capacity" name="slot_capacity" class="form-control"
                                value="5" min="1">
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
                    <button type="submit" class="btn btn-outline-success w-100">Create Order Date</button>
                </div>
            </div>
        </div>
    </form>
@endsection

@section('javascript')
    <script>
        $(document).ready(function() {
            $('#auto_generate').on('change', function() {
                if ($(this).is(':checked')) {
                    $('#slot_fields, #slot_fields_end, #slot_fields_capacity').show();
                } else {
                    $('#slot_fields, #slot_fields_end, #slot_fields_capacity').hide();
                }
            });
        });
    </script>
@endsection
