@extends('layouts.backend')

@section('content')
    <div class="pagetitle">
        <h1>Edit Group</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                <li class="breadcrumb-item">User Management</li>
                <li class="breadcrumb-item"><a href="{{ route('groups.index') }}">Groups</a></li>
                <li class="breadcrumb-item active">Edit Group</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <div class="row justify-content-center" style="margin-left: 350px; margin-right: 50px;">
            <div class="col-lg-10">
                <div class="card shadow-lg">
                    <div class="card-body">
                        <h5 class="card-title">Edit Group: {{ $group->name }}</h5>

                        <form action="{{ route('groups.update', $group->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="mb-3">
                                <label for="name" class="form-label">Group Name</label>
                                <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $group->name) }}" required>
                            </div>
                            <div class="mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control" id="description" name="description" rows="3">{{ old('description', $group->description) }}</textarea>
                            </div>

                            <hr>
                            <h5 class="card-title">Assign Roles</h5>
                            <div class="mb-3">
                                <label for="roles" class="form-label">Roles</label>
                                <select class="form-select" id="roles" name="roles[]" multiple style="height: 150px;">
                                    @foreach($roles as $role)
                                        <option value="{{ $role->id }}" {{ $group->roles->contains($role->id) ? 'selected' : '' }}>
                                            {{ $role->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="form-text">Hold down the Ctrl (windows) / Command (Mac) button to select multiple options.</div>
                            </div>

                            <div class="text-end">
                                <button type="submit" class="btn btn-primary">Update Group</button>
                                <a href="{{ route('groups.index') }}" class="btn btn-secondary">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection 