@extends('layouts.backend')
@section('content')

    <main id="main" class="main">

        <div class="pagetitle">
            <h1>Outgoing Referrals Report</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Reports</a></li>
                    <li class="breadcrumb-item active">Outgoing Referrals Report</li>
                </ol>
            </nav>
        </div><!-- End Page Title -->

        <section class="section dashboard">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Filter Report</h5>
                            <form method="GET" action="{{ route('reports.outgoing') }}">
                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <label for="status" class="form-label">Status</label>
                                        <select name="status" id="status" class="form-select">
                                            <option value="">All Statuses</option>
                                            @foreach($statuses as $status)
                                                <option value="{{ $status }}" {{ isset($filters['status']) && $filters['status'] == $status ? 'selected' : '' }}>{{ $status }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-2 d-flex align-items-end">
                                        <button type="submit" class="btn btn-primary">Filter</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Outgoing Referrals</h5>
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
                                            <td colspan="7" class="text-center">No outgoing referrals found.</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            <div class="mt-3">
                                {{ $referrals->appends(request()->query())->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

    </main>
@endsection