@extends('layouts.backend')

@section('content')
    <div class="pagetitle">
        <h1>Verify Referral</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                <li class="breadcrumb-item">Verification</li>
                <li class="breadcrumb-item active">Verify Referral</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow-lg">
                    <div class="card-body">
                        <h5 class="card-title">Verify Referral by ID</h5>
                        <form action="{{ route('verification.referral') }}" method="GET">
                            <div class="input-group mb-3">
                                <input type="text" class="form-control" name="referral_id" placeholder="Enter Referral ID" value="{{ request('referral_id') }}" required>
                                <button class="btn btn-primary" type="submit">Search</button>
                            </div>
                        </form>

                        @if(request()->filled('referral_id'))
                            @if($referral)
                                <div class="alert alert-success mt-3">
                                    <h4 class="alert-heading">Referral Found!</h4>
                                    <p><strong>Referral ID:</strong> {{ $referral->id }}</p>
                                    <p><strong>Patient Name:</strong> {{ $referral->patient->name ?? 'N/A' }}</p>
                                    <p><strong>Status:</strong> <span class="badge bg-info">{{ $referral->status }}</span></p>
                                    <p><strong>Referring Facility:</strong> {{ $referral->referring_facility_id }}</p>
                                    <p><strong>Referred To:</strong> {{ $referral->referredFacility }}</p>
                                </div>
                            @else
                                <div class="alert alert-danger mt-3">
                                    <strong>Referral not found.</strong> No referral with the provided ID exists in the system.
                                </div>
                            @endif
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection 