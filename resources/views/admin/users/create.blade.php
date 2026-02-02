@extends('admin.layouts.app')

@section('content')
    <form id="userForm">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">User Information</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="mb-3">
                            <label for="name" class="form-label">User Name</label>
                            <input type="text" id="name" name="name" class="form-control" placeholder="User Name"
                                required>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="text" id="email" name="email" class="form-control" placeholder="Email"
                                required>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="mb-3">
                            <label for="phone" class="form-label">Phone</label>
                            <input type="text" id="phone" name="phone" class="form-control" placeholder="Phone"
                                required>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" id="password" name="password" class="form-control"
                                placeholder="Password" required>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="mb-3">
                            <label for="role" class="form-label">Role</label>
                            <select name="role" id="role" class="form-select">
                                <option value="">Select Role</option>
                                <option value="user">User</option>
                                <option value="admin">Admin</option>
                            </select>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <div class="p-3 bg-light mb-3 rounded">
            <div class="row justify-content-end g-2">
                <div class="col-lg-2">
                    <button type="submit" class="btn btn-outline-secondary w-100">Create User</button>
                </div>
            </div>
        </div>
    </form>
@endsection


@section('javascript')
    <script>
        $(document).ready(function() {


            $("#userForm").on("submit", function(e) {
                e.preventDefault();

                let form = this;
                let formData = new FormData(form);

                $.ajax({
                    url: "{{ route('admin.users.store') }}",
                    type: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    beforeSend: function() {
                        $('#userForm .is-invalid').removeClass('is-invalid');
                        $('#userForm .invalid-feedback').remove();
                    },
                    success: function(response) {
                        window.location.href = "{{ route('admin.users.index') }}";
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
