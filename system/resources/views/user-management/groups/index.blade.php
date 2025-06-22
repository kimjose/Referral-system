@extends('layouts.backend')

@section('content')
    <div class="pagetitle">
        <h1>Groups</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                <li class="breadcrumb-item">User Management</li>
                <li class="breadcrumb-item active">Groups</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <div class="row justify-content-center" style="margin-left: 350px; margin-right: 50px;">
            <div class="col-lg-10">
                <div class="card shadow-lg">
                    <div class="card-body">
                        <h5 class="card-title">All Groups
                            <a href="{{ route('groups.create') }}" class="btn btn-success btn-sm float-end">Add New Group</a>
                        </h5>

                        <div style="overflow-x: auto;">
                            <table class="table table-striped align-middle">
                                <thead class="table-primary">
                                    <tr>
                                        <th>Name</th>
                                        <th>Description</th>
                                        <th>Users Count</th>
                                        <th>Created At</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($groups as $group)
                                        <tr>
                                            <td>{{ $group->name }}</td>
                                            <td>{{ $group->description }}</td>
                                            <td>{{ $group->users_count }}</td>
                                            <td>{{ $group->created_at->format('d M Y') }}</td>
                                            <td>
                                                <div class="d-flex gap-2">
                                                    <a href="{{ route('groups.edit', $group->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                                    <form action="{{ route('groups.destroy', $group->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this group?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center">No groups found.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="d-flex justify-content-center mt-3">
                            {{ $groups->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection 