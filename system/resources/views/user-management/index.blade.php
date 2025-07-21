@extends('layouts.backend')

@section('content')
    <div class="pagetitle">
        <h1>User Management</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                <li class="breadcrumb-item active">Users</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="row justify-content-center" style="margin-left: 350px; margin-right: 50px;">
            <div class="col-lg-10">
                <div class="card shadow-lg">
                    <div class="card-body">
                        <h5 class="card-title">Users</h5>

                        @can('create users')
                        <a href="{{ route('user-management.create') }}" class="btn btn-success mb-3">Add New User</a>
                        @endcan

                        <!-- Search and Filter Form -->
                        <form action="{{ route('user-management.index') }}" method="GET" class="mb-3">
                            <div class="row">
                                <div class="col-md-3">
                                    <input type="text" name="search" class="form-control" placeholder="Search by name, email..." value="{{ request('search') }}">
                                </div>
                                <div class="col-md-2">
                                    <select name="role" class="form-select">
                                        <option value="">All Roles</option>
                                        @foreach($roles as $role)
                                            <option value="{{ $role->name }}" {{ request('role') == $role->name ? 'selected' : '' }}>{{ $role->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <select name="group" class="form-select">
                                        <option value="">All Groups</option>
                                        @foreach($groups as $group)
                                            <option value="{{ $group->id }}" {{ request('group') == $group->id ? 'selected' : '' }}>{{ $group->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <select name="status" class="form-select">
                                        <option value="">All Statuses</option>
                                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                        <option value="suspended" {{ request('status') == 'suspended' ? 'selected' : '' }}>Suspended</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <button type="submit" class="btn btn-primary">Filter</button>
                                </div>
                            </div>
                        </form>

                        <!-- Users Table -->
                        <div style="overflow-x: auto;">
                            <table class="table table-striped align-middle">
                                <thead class="table-primary">
                                    <tr>
                                        <th><a href="{{ route('user-management.index', ['sort_by' => 'name', 'sort_order' => request('sort_order') === 'asc' ? 'desc' : 'asc']) }}">Name</a></th>
                                        <th><a href="{{ route('user-management.index', ['sort_by' => 'email', 'sort_order' => request('sort_order') === 'asc' ? 'desc' : 'asc']) }}">Email</a></th>
                                        <th>Roles</th>
                                        <th>Groups</th>
                                        <th>Status</th>
                                        <th><a href="{{ route('user-management.index', ['sort_by' => 'created_at', 'sort_order' => request('sort_order') === 'asc' ? 'desc' : 'asc']) }}">Joined On</a></th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($users as $user)
                                        <tr>
                                            <td>{{ $user->name }}</td>
                                            <td>{{ $user->email }}</td>
                                            <td>{{ $user->roles->pluck('name')->implode(', ') }}</td>
                                            <td>{{ $user->groups->pluck('name')->implode(', ') }}</td>
                                            <td>
                                                <span class="badge bg-{{ $user->status == 'active' ? 'success' : ($user->status == 'inactive' ? 'secondary' : 'warning') }}">
                                                    {{ ucfirst($user->status) }}
                                                </span>
                                            </td>
                                            <td>{{ $user->created_at->format('d M Y') }}</td>
                                            <td>
                                                <div class="d-flex gap-2">
                                                    @can('edit users')
                                                    <a href="{{ route('user-management.edit', $user->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                                    @endcan
                                                    @can('delete users')
                                                    <form action="{{ route('user-management.destroy', $user->id) }}" method="POST" style="display:inline;">
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
                                            <td colspan="7" class="text-center">No users found.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Modern Minimal Pagination and Summary Section -->
                        <hr class="mt-5 mb-4">
                        <div class="d-flex flex-column align-items-center mb-5">
                            <div class="text-muted mb-2 small">
                                <i class="bi bi-people-fill"></i>
                                Showing <strong>{{ $users->firstItem() }}</strong> to <strong>{{ $users->lastItem() }}</strong> of <strong>{{ $users->total() }}</strong> users
                            </div>
                            <nav aria-label="User pagination">
                                {{ $users->withQueryString()->links() }}
                            </nav>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('styles')
<style>
    .pagination .page-link {
        font-size: 0.85rem;
        padding: 0.25rem 0.5rem;
    }
    .pagination .page-link svg,
    .pagination .page-link i,
    .pagination svg,
    .pagination li svg,
    .pagination li .page-link svg {
        width: 1em !important;
        height: 1em !important;
        max-width: 20px !important;
        max-height: 20px !important;
        min-width: 10px !important;
        min-height: 10px !important;
        vertical-align: middle;
        stroke-width: 2 !important;
    }
    /* Force chevron icon size in pagination */
    .pagination .page-link i.bi {
        font-size: 1rem !important;
        width: 1em !important;
        height: 1em !important;
        vertical-align: middle;
    }
    /* Fix for any h1 in pagination (in case of markup error) */
    .pagination h1 {
        font-size: 1rem !important;
        line-height: 1.2 !important;
        margin: 0 !important;
        display: inline !important;
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.pagination svg').forEach(function(svg) {
        svg.style.width = '1em';
        svg.style.height = '1em';
        svg.setAttribute('width', '1em');
        svg.setAttribute('height', '1em');
    });
});
</script>
@endpush 