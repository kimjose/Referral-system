@extends('layouts.backend')

@section('content')
    <div class="pagetitle">
        <h1>Role Management</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('role-management.index') }}">Roles</a></li>
                <li class="breadcrumb-item active">Add New Role</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="row justify-content-center" style="margin-left: 350px; margin-right: 50px;">
            <div class="col-lg-10">
                <div class="card shadow-lg">
                    <div class="card-body">
                        <h5 class="card-title">Add New Role</h5>

                        <form action="{{ route('role-management.store') }}" method="POST">
                            @csrf

                            <div class="row mb-3">
                                <label for="name" class="col-sm-2 col-form-label">Role Name</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required placeholder="Enter role name">
                                </div>
                            </div>

                            <hr>

                            <h5 class="card-title">Assign Permissions</h5>

                            <div class="row">
                                @foreach($permissions as $group => $permissionGroup)
                                    <div class="col-md-4 mb-3">
                                        <div class="card border-primary">
                                            <div class="card-header bg-primary text-white">
                                                <strong>{{ ucfirst($group) }}</strong>
                                            </div>
                                            <div class="card-body mt-2">
                                                @foreach($permissionGroup as $permission)
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" name="permissions[]" value="{{ $permission->name }}" id="permission_{{ $permission->id }}">
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
                                    <button type="submit" class="btn btn-success">Create Role</button>
                                    <a href="{{ route('role-management.index') }}" class="btn btn-secondary ms-2">Cancel</a>
                                </div>
                            </div>

                        </form>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection 