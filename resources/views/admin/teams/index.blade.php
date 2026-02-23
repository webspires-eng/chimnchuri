@extends('admin.layouts.app')

@section('content')
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center gap-1">
                    <h4 class="card-title flex-grow-1">All Team members</h4>

                    <a href="{{ route('admin.teams.create') }}" class="btn btn-sm btn-primary">
                        Add Team Member
                    </a>
                </div>
                <div>
                    <div class="table-responsive">
                        <table class="table align-middle mb-0 table-hover table-centered">
                            <thead class="bg-light-subtle">
                                <tr>
                                    <th style="width: 20px;">#ID</th>
                                    <th>Image</th>
                                    <th>Name</th>
                                    <th>Role</th>
                                    <th>Email</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($teams as $team)
                                    <tr>
                                        <td>{{ $team->id }}</td>
                                        <td>
                                            <div
                                                class="rounded overflow-hidden bg-light avatar-md d-flex align-items-center justify-content-center">
                                                <img src="{{ $team->image ? asset($team->image) : asset('admin/assets/images/users/avatar-1.jpg') }}"
                                                    alt="{{ $team->name }}" class="avatar-md">
                                            </div>
                                        </td>
                                        <td>
                                            <a href="#!" class="text-dark fw-medium fs-15">{{ $team->name }}</a>
                                        </td>
                                        <td>{{ $team->role }}</td>
                                        <td>{{ $team->email }}</td>
                                        <td>
                                            <div class="d-flex gap-2">
                                                <a href="{{ route('admin.teams.edit', $team->id) }}"
                                                    class="btn btn-soft-primary btn-sm">
                                                    <iconify-icon icon="solar:pen-2-broken"
                                                        class="align-middle fs-18"></iconify-icon>
                                                </a>
                                                <form action="{{ route('admin.teams.destroy', $team->id) }}" method="POST"
                                                    onsubmit="return confirm('Are you sure you want to remove this team member?');">
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
                                        <td colspan="6" class="text-center">No team members found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer border-top">
                    <nav aria-label="Page navigation example">
                        {{ $teams->links() }}
                    </nav>
                </div>
            </div>
        </div>
    </div>
@endsection
