@extends('layouts.backend')
@section('content')
    <main id="main" class="main">

        <div class="pagetitle">
            <h1>{{ $pageTitle ?? 'Disaggregate Report' }}</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item">Reports</li>
                    <li class="breadcrumb-item active">{{ $pageTitle ?? 'Disaggregate' }}</li>
                </ol>
            </nav>
        </div><!-- End Page Title -->

        <section class="section">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Filter Referrals</h5>

                            <form method="GET" action="{{ route('admin.reports.disaggregate') }}">
                                <div class="row">
                                    <div class="col-md-3">
                                        <label for="from_date" class="form-label">From</label>
                                        <input type="date" name="from_date" id="from_date" class="form-control" value="{{ $filters['from_date'] ?? '' }}">
                                    </div>
                                    <div class="col-md-3">
                                        <label for="to_date" class="form-label">To</label>
                                        <input type="date" name="to_date" id="to_date" class="form-control" value="{{ $filters['to_date'] ?? '' }}">
                                    </div>
                                    <div class="col-md-3">
                                        <label for="status" class="form-label">Status</label>
                                        <select name="status" id="status" class="form-select">
                                            <option value="">All Statuses</option>
                                            @foreach($statuses as $status)
                                                <option value="{{ $status }}" {{ isset($filters['status']) && $filters['status'] == $status ? 'selected' : '' }}>{{ $status }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="facility_id" class="form-label">Facility</label>
                                        <select name="facility_id" id="facility_id" class="form-select">
                                            <option value="">All Facilities</option>
                                            @foreach($facilities as $facility)
                                                <option value="{{ $facility->Code }}" {{ isset($filters['facility_id']) && $filters['facility_id'] == $facility->Code ? 'selected' : '' }}>{{ $facility->Officialname }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-md-12 text-end">
                                        <a href="{{ route('admin.reports.disaggregate') }}" class="btn btn-secondary">Clear</a>
                                        <button type="submit" class="btn btn-primary">Filter</button>
                                        <button type="submit" name="export" value="csv" class="btn btn-success">Export as CSV</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">{{ $pageTitle ?? 'Detailed Referral Report' }}</h5>
                            
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Referral ID</th>
                                            <th>Client UPI</th>
                                            <th>Referring Officer</th>
                                            <th>Referred To Facility</th>
                                            <th>Service</th>
                                            <th>Status</th>
                                            <th>Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($referrals as $referral)
                                        <tr>
                                            <td>{{ $referral->referralId }}</td>
                                            <td>{{ $referral->clientUPI }}</td>
                                            <td>{{ $referral->referringOfficer }}</td>
                                            <td>{{ $referral->referredToFacility->Officialname ?? $referral->referredFacility }}</td>
                                            <td>{{ $referral->service }}</td>
                                            <td><span class="badge bg-info text-dark">{{ $referral->fhir_status }}</span></td>
                                            <td>{{ $referral->created_at->format('Y-m-d') }}</td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="7" class="text-center">No referrals found for the selected criteria.</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            
                            <!-- Pagination -->
                            <div class="mt-3">
                                {{ $referrals->appends(request()->query())->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

    </main><!-- End #main -->
@endsection 