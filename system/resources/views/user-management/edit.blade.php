@extends('layouts.backend')

@section('content')
    <div class="pagetitle">
        <h1>User Management</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('user-management.index') }}">Users</a></li>
                <li class="breadcrumb-item active">Edit User</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="row justify-content-center" style="margin-left: 350px; margin-right: 50px;">
            <div class="col-lg-10">
                <div class="card shadow-lg">
                    <div class="card-body">
                        <h5 class="card-title">Edit User: {{ $user->name }}</h5>
                        <form action="{{ route('user-management.update', $user->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="row mb-3">
                                <label for="name" class="col-sm-2 col-form-label">Name</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $user->name) }}" required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="email" class="col-sm-2 col-form-label">Email</label>
                                <div class="col-sm-10">
                                    <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                                </div>
                            </div>
                             <div class="row mb-3">
                                <label for="phone" class="col-sm-2 col-form-label">Phone</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="phone" name="phone" value="{{ old('phone', $user->phone) }}">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="facility" class="col-sm-2 col-form-label">Facility</label>
                                <div class="col-sm-10">
                                    <select class="form-select" id="facility" name="facility_id">
                                        <option value="">No Facility</option>
                                        @foreach($facilities as $facility)
                                            <option value="{{ $facility->id }}" {{ old('facility_id', $user->facility_id) == $facility->id ? 'selected' : '' }}>
                                                {{ $facility->Officialname }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="role" class="col-sm-2 col-form-label">Role</label>
                                <div class="col-sm-10">
                                    <select class="form-select" id="role" name="role" required>
                                        @foreach($roles as $role)
                                            <option value="{{ $role->name }}" {{ $user->hasRole($role->name) ? 'selected' : '' }}>
                                                {{ $role->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="status" class="col-sm-2 col-form-label">Status</label>
                                <div class="col-sm-10">
                                    <select class="form-select" id="status" name="status" required>
                                        <option value="active" {{ old('status', $user->status) == 'active' ? 'selected' : '' }}>Active</option>
                                        <option value="inactive" {{ old('status', $user->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                        <option value="suspended" {{ old('status', $user->status) == 'suspended' ? 'selected' : '' }}>Suspended</option>
                                    </select>
                                </div>
                            </div>
                            <hr>
                            <h5 class="card-title">Assign Groups</h5>
                            <div class="row mb-3">
                                <label for="groups" class="col-sm-2 col-form-label">Groups</label>
                                <div class="col-sm-10">
                                    <select class="form-select" id="groups" name="groups[]" multiple>
                                        @foreach($groups as $group)
                                            <option value="{{ $group->id }}" {{ $user->groups->contains($group->id) ? 'selected' : '' }}>
                                                {{ $group->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="form-text">Hold down the Ctrl (windows) / Command (Mac) button to select multiple options.</div>
                                </div>
                            </div>
                            <hr>
                            <h5 class="card-title">Assign Permissions</h5>
                            <div class="row">
                                @php
                                    $groupedPermissions = $permissions->groupBy(function($permission) {
                                        $parts = explode(' ', $permission->name);
                                        if ($parts[0] === 'verify') {
                                            return 'verification';
                                        }
                                        return $parts[1] ?? 'general';
                                    });
                                @endphp
                                @foreach($groupedPermissions as $group => $permissionGroup)
                                    <div class="col-md-4 mb-3">
                                        <div class="card border-primary">
                                            <div class="card-header bg-primary text-white">
                                                <strong>{{ ucfirst($group) }}</strong>
                                            </div>
                                            <div class="card-body mt-2">
                                                @foreach($permissionGroup as $permission)
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" name="permissions[]" value="{{ $permission->name }}" id="permission_{{ $permission->id }}"
                                                               {{ $user->hasPermissionTo($permission->name) ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="permission_{{ $permission->id }}">
                                                            {{ $permission->name }}
                                                        </label>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <div class="row mt-4">
                                <div class="col-sm-10 offset-sm-2">
                                    <button type="submit" class="btn btn-success">Update User</button>
                                    <a href="{{ route('user-management.index') }}" class="btn btn-secondary ms-2">Cancel</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection 