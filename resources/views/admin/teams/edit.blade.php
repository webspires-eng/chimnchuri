@extends('admin.layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <form action="{{ route('admin.teams.update', $team->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Edit Team Member</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Full Name</label>
                                    <input type="text" id="name" name="name"
                                        class="form-control @error('name') is-invalid @enderror"
                                        placeholder="Enter Full Name" value="{{ old('name', $team->name) }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="role" class="form-label">Role / Designation</label>
                                    <input type="text" id="role" name="role"
                                        class="form-control @error('role') is-invalid @enderror"
                                        placeholder="e.g. Founder & Executive Chef" value="{{ old('role', $team->role) }}"
                                        required>
                                    @error('role')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email Address</label>
                                    <input type="email" id="email" name="email"
                                        class="form-control @error('email') is-invalid @enderror"
                                        placeholder="email@example.com" value="{{ old('email', $team->email) }}">
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="image" class="form-label">Profile Image</label>
                                    <div class="mb-2">
                                        <img src="{{ $team->image ? asset($team->image) : asset('admin/assets/images/users/avatar-1.jpg') }}"
                                            alt="{{ $team->name }}" class="avatar-lg rounded border">
                                    </div>
                                    <input type="file" id="image" name="image"
                                        class="form-control @error('image') is-invalid @enderror">
                                    <small class="text-muted">Leave empty to keep current image. Max size: 2MB.</small>
                                    @error('image')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-12">
                                <div class="mb-3">
                                    <label for="bio" class="form-label">Bio / Description</label>
                                    <textarea class="form-control bg-light-subtle @error('bio') is-invalid @enderror" id="bio" rows="5"
                                        placeholder="In short, describe the team member..." name="bio">{{ old('bio', $team->bio) }}</textarea>
                                    @error('bio')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="p-3 bg-light mb-3 rounded">
                    <div class="row justify-content-end g-2">
                        <div class="col-lg-2">
                            <button type="submit" class="btn btn-primary w-100">Update Team Member</button>
                        </div>
                        <div class="col-lg-2">
                            <a href="{{ route('admin.teams.index') }}" class="btn btn-outline-secondary w-100">Cancel</a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
