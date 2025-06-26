@extends('layouts.backend')

@section('content')
    <div class="pagetitle">
        <h1>Role Management</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                <li class="breadcrumb-item active">Roles</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="row justify-content-center" style="margin-left: 350px; margin-right: 50px;">
            <div class="col-lg-10">
                <div class="card shadow-lg">
                    <div class="card-body">
                        <h5 class="card-title">Roles</h5>

                        @can('create roles')
                        <a href="{{ route('role-management.create') }}" class="btn btn-success mb-3">Add New Role</a>
                        @endcan

                        <div style="overflow-x: auto;">
                            <table class="table table-striped align-middle">
                                <thead class="table-primary">
                                    <tr>
                                        <th style="min-width: 180px; font-weight: bold;">Role Name</th>
                                        <th>Users</th>
                                        <th>Permissions</th>
                                        <th>Created At</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    
                                    @forelse($roles as $role)
                                        <tr>
                                            <td style="font-weight: bold; color: #2c3e50;">{{ $role->name ?: 'N/A' }}</td>
                                            <td>{{ $role->users_count }}</td>
                                            <td>{{ $role->permissions_count }}</td>
                                            <td>{{ $role->created_at->format('d M Y') }}</td>
                                            <td>
                                                <div class="d-flex gap-2">
                                                    @can('edit roles')
                                                    <a href="{{ route('role-management.edit', $role->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                                    @endcan
                                                    @can('delete roles')
                                                    <form action="{{ route('role-management.destroy', $role->id) }}" method="POST" style="display:inline;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                                                    </form>
                                                    @endcan
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center">No roles found.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection 