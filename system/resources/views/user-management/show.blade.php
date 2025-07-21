@extends('layouts.backend')

@section('content')
    <div class="pagetitle">
        <h1>User Profile</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('user-management.index') }}">Users</a></li>
                <li class="breadcrumb-item active">Profile</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="row justify-content-center" style="margin-left: 350px; margin-right: 50px;">
            <div class="col-lg-10">
                <div class="card shadow-lg">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-4">
                            <img src="{{ $user->profile_picture ? asset('storage/' . $user->profile_picture) : asset('system/public/assets/img/profile-img.jpg') }}" alt="Profile Picture" class="rounded-circle me-4" style="width: 100px; height: 100px; object-fit: cover; border: 3px solid #eee;">
                            <div>
                                <h3 class="mb-1">{{ $user->name }}</h3>
                                <div class="mb-1">
                                    <span class="badge bg-primary"><i class="bi bi-person-badge"></i> {{ $user->roles->pluck('name')->implode(', ') }}</span>
                                    <span class="badge bg-{{ $user->status == 'active' ? 'success' : ($user->status == 'inactive' ? 'secondary' : 'warning') }} ms-2">
                                        <i class="bi bi-circle-fill"></i> {{ ucfirst($user->status) }}
                                    </span>
                                </div>
                                <div class="text-muted small">Joined: {{ $user->created_at ? $user->created_at->format('d M Y') : '-' }}</div>
                            </div>
                        </div>
                        <hr>
                        <div class="row g-4 mb-4">
                            <div class="col-md-6">
                                <div class="d-flex align-items-center mb-3">
                                    <i class="bi bi-person fs-4 text-primary me-3"></i>
                                    <div>
                                        <div class="fw-bold">Username</div>
                                        <div>{{ $user->username ?: '-' }}</div>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center mb-3">
                                    <i class="bi bi-envelope fs-4 text-primary me-3"></i>
                                    <div>
                                        <div class="fw-bold">Email</div>
                                        <div>{{ $user->email }}</div>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center mb-3">
                                    <i class="bi bi-telephone fs-4 text-primary me-3"></i>
                                    <div>
                                        <div class="fw-bold">Phone</div>
                                        <div>{{ $user->phone ?: '-' }}</div>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center mb-3">
                                    <i class="bi bi-geo-alt fs-4 text-primary me-3"></i>
                                    <div>
                                        <div class="fw-bold">Address</div>
                                        <div>{{ $user->address ?: '-' }}</div>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center mb-3">
                                    <i class="bi bi-hospital fs-4 text-primary me-3"></i>
                                    <div>
                                        <div class="fw-bold">Facility</div>
                                        <div>{{ $user->userFacility ? $user->userFacility->Officialname : 'No Facility' }}</div>
                                    </div>
                                </div>
                                @if($user->bio)
                                <div class="d-flex align-items-center mb-3">
                                    <i class="bi bi-card-text fs-4 text-primary me-3"></i>
                                    <div>
                                        <div class="fw-bold">Bio</div>
                                        <div>{{ $user->bio }}</div>
                                    </div>
                                </div>
                                @endif
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex align-items-center mb-3">
                                    <i class="bi bi-people fs-4 text-primary me-3"></i>
                                    <div>
                                        <div class="fw-bold">Groups</div>
                                        <div>{{ $user->groups->pluck('name')->implode(', ') ?: '-' }}</div>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center mb-3">
                                    <i class="bi bi-shield-lock fs-4 text-primary me-3"></i>
                                    <div>
                                        <div class="fw-bold">Permissions</div>
                                        <div class="small text-muted">{{ $user->permissions->pluck('name')->implode(', ') ?: '-' }}</div>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center mb-3">
                                    <i class="bi bi-shield-check fs-4 text-primary me-3"></i>
                                    <div>
                                        <div class="fw-bold">Security</div>
                                        <div class="small">Last Login: {{ $user->last_login_at ? $user->last_login_at->format('d M Y, H:i') : '-' }}</div>
                                        <div class="small">Last Login IP: {{ $user->last_login_ip ?: '-' }}</div>
                                        @if($user->force_password_change)
                                            <div class="small text-danger"><i class="bi bi-exclamation-triangle"></i> Password change required</div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <h5 class="card-title mb-3"><i class="bi bi-clock-history me-2"></i>Recent Activity</h5>
                        @if(!empty($activityLog) && count($activityLog))
                            <ul class="timeline list-unstyled mb-4">
                                @foreach($activityLog as $activity)
                                    <li class="mb-3 position-relative ps-4">
                                        <span class="position-absolute top-0 start-0 translate-middle p-2 bg-primary border border-light rounded-circle"></span>
                                        <div class="fw-bold">{{ $activity['action'] ?? $activity->action }}</div>
                                        <div>{{ $activity['description'] ?? $activity->description }}</div>
                                        <div class="text-muted small">{{ $activity['created_at'] ?? $activity->created_at }}</div>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <p class="text-muted">No recent activity found.</p>
                        @endif
                        <div class="mt-4">
                            <a href="{{ route('user-management.edit', $user->id) }}" class="btn btn-primary"><i class="bi bi-pencil-square me-1"></i> Edit Profile</a>
                            <a href="{{ route('user-management.index') }}" class="btn btn-secondary ms-2"><i class="bi bi-arrow-left me-1"></i> Back to Users</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <style>
        .timeline li::before {
            content: '';
            position: absolute;
            left: 0.5rem;
            top: 0.5rem;
            width: 8px;
            height: 8px;
            background: #0d6efd;
            border-radius: 50%;
        }
        .timeline li {
            border-left: 2px solid #0d6efd;
            margin-left: 1.5rem;
            padding-left: 1rem;
        }
    </style>
@endsection 