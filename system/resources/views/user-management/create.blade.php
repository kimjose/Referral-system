@extends('layouts.backend')

@section('content')
    <div class="pagetitle">
        <h1>Add New User</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                <li class="breadcrumb-item">User Management</li>
                <li class="breadcrumb-item"><a href="{{ route('user-management.index') }}">Users</a></li>
                <li class="breadcrumb-item active">Add New User</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <div class="row justify-content-center" style="margin-left: 350px; margin-right: 50px;">
            <div class="col-lg-10">
                <div class="card shadow-lg">
                    <div class="card-body">
                        <h5 class="card-title">User Details</h5>

                        <form action="{{ route('user-management.store') }}" method="POST">
                            @csrf
                            <div class="row mb-3">
                                <label for="name" class="col-sm-2 col-form-label">Name</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="email" class="col-sm-2 col-form-label">Email</label>
                                <div class="col-sm-10">
                                    <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="phone" class="col-sm-2 col-form-label">Phone</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="phone" name="phone" value="{{ old('phone') }}">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="facility" class="col-sm-2 col-form-label">Facility</label>
                                <div class="col-sm-10">
                                    <select class="form-select" id="facility" name="facility_id">
                                        <option value="">No Facility</option>
                                        @foreach($facilities as $facility)
                                            <option value="{{ $facility->id }}">{{ $facility->Officialname }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="role" class="col-sm-2 col-form-label">Role</label>
                                <div class="col-sm-10">
                                    <select class="form-select" id="role" name="role_id" required>
                                        @foreach($roles as $role)
                                            <option value="{{ $role->id }}">{{ $role->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="groups" class="col-sm-2 col-form-label">Groups</label>
                                <div class="col-sm-10">
                                    <select class="form-select" id="groups" name="groups[]" multiple>
                                        @foreach($groups as $group)
                                            <option value="{{ $group->id }}">{{ $group->name }}</option>
                                        @endforeach
                                    </select>
                                    <div class="form-text">Hold down the Ctrl (windows) / Command (Mac) button to select multiple options.</div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="status" class="col-sm-2 col-form-label">Status</label>
                                <div class="col-sm-10">
                                    <select class="form-select" id="status" name="status" required>
                                        <option value="active" selected>Active</option>
                                        <option value="inactive">Inactive</option>
                                        <option value="suspended">Suspended</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="password" class="col-sm-2 col-form-label">Password</label>
                                <div class="col-sm-10">
                                    <input type="password" class="form-control" id="password" name="password" required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="password_confirmation" class="col-sm-2 col-form-label">Confirm Password</label>
                                <div class="col-sm-10">
                                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                                </div>
                            </div>
                            <div class="row mt-4">
                                <div class="col-sm-10 offset-sm-2">
                                    <button type="submit" class="btn btn-success">Create User</button>
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