@extends('layouts.backend')

@section('content')
    <div class="pagetitle">
        <h1>Add New Group</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                <li class="breadcrumb-item">User Management</li>
                <li class="breadcrumb-item"><a href="{{ route('groups.index') }}">Groups</a></li>
                <li class="breadcrumb-item active">Add New Group</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <div class="row justify-content-center" style="margin-left: 350px; margin-right: 50px;">
            <div class="col-lg-10">
                <div class="card shadow-lg">
                    <div class="card-body">
                        <h5 class="card-title">Group Details</h5>

                        <form action="{{ route('groups.store') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="name" class="form-label">Group Name</label>
                                <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required>
                            </div>
                            <div class="mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control" id="description" name="description" rows="3">{{ old('description') }}</textarea>
                            </div>
                            <div class="text-end">
                                <button type="submit" class="btn btn-success">Create Group</button>
                                <a href="{{ route('groups.index') }}" class="btn btn-secondary">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection 