@extends('admin.layouts.app')

@section('content')
    <form action="{{ route('admin.time-slots.store') }}" method="POST">
        @csrf
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Time Slot Information</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-4">
                        <div class="mb-3">
                            <label for="start_time" class="form-label">Start Time</label>
                            <input type="time" id="start_time" name="start_time" class="form-control"
                                placeholder="Start Time" required>
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div class="mb-3">
                            <label for="end_time" class="form-label">End Time</label>
                            <input type="time" id="end_time" name="end_time" class="form-control" placeholder="End Time"
                                required>
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div class="mb-3">
                            <label for="max_capacity" class="form-label">Max Capacity</label>
                            <input type="number" id="max_capacity" name="max_capacity" class="form-control"
                                placeholder="Max Capacity" required>
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div class="mb-3">
                            <label for="is_active" class="form-label">Is Active</label>
                            <div class="form-check form-switch">
                                <input type="hidden" name="is_active" value="0">
                                <input class="form-check-input" type="checkbox" role="switch" id="is_active"
                                    name="is_active" value="1" checked>
                            </div>
                        </div>
                    </div>


                </div>

            </div>
        </div>
        <div class="p-3 bg-light mb-3 rounded">
            <div class="row justify-content-end g-2">
                <div class="col-lg-2">
                    <button type="submit" class="btn btn-outline-success w-100">Create Time Slot</button>
                </div>
            </div>
        </div>
    </form>
@endsection


@section('javascript')
    <script>
        $(document).ready(function() {


            $("#addonItemForm").on("submit", function(e) {
                e.preventDefault();

                let form = this;
                let formData = new FormData(form);

                $.ajax({
                    url: "{{ route('admin.addon-items.store') }}",
                    type: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    beforeSend: function() {
                        $('#addonItemForm .is-invalid').removeClass('is-invalid');
                        $('#addonItemForm .invalid-feedback').remove();
                    },
                    success: function(response) {
                        window.location.href = "{{ route('admin.addon-items.index') }}";
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            let errors = xhr.responseJSON.errors;
                            $.each(errors, function(key, value) {
                                let input = $(`[name="${key}"]`);
                                if (input.length > 0) {
                                    input.addClass('is-invalid');
                                    if (input.next('.invalid-feedback').length === 0) {
                                        input.after(
                                            `<div class="invalid-feedback">${value[0]}</div>`
                                        );
                                    } else {
                                        input.next('.invalid-feedback').text(value[0]);
                                    }
                                }
                            });
                        } else {
                            alert("Something went wrong. Check server logs.");
                        }
                    }
                })


            });
        });
    </script>
@endsection
