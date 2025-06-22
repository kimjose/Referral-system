@extends('layouts.backend')

@section('content')
    <div class="pagetitle">
        <h1>Verify Patient</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                <li class="breadcrumb-item">Verification</li>
                <li class="breadcrumb-item active">Verify Patient</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow-lg">
                    <div class="card-body">
                        <h5 class="card-title">Verify Patient by ID Number</h5>
                        <form action="{{ route('verification.patient') }}" method="GET">
                            <div class="input-group mb-3">
                                <input type="text" class="form-control" name="patient_id" placeholder="Enter Patient ID Number" value="{{ request('patient_id') }}" required>
                                <button class="btn btn-primary" type="submit">Search</button>
                            </div>
                        </form>

                        @if(request()->filled('patient_id'))
                            @if($patient)
                                <div class="alert alert-success mt-3">
                                    <h4 class="alert-heading">Patient Found!</h4>
                                    <p><strong>Name:</strong> {{ $patient->first_name }} {{ $patient->last_name }}</p>
                                    <p><strong>ID Number:</strong> {{ $patient->idNo }}</p>
                                    <p><strong>Date of Birth:</strong> {{ $patient->dob }}</p>
                                </div>
                            @else
                                <div class="alert alert-danger mt-3">
                                    <strong>Patient not found.</strong> No patient with the provided ID number exists in the system.
                                </div>
                            @endif
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection 