@extends('layouts.backend')
@section('content')
    <main id="main" class="main">

        <div class="pagetitle">
            <h1>Aggregate Report</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item">Reports</li>
                    <li class="breadcrumb-item active">Aggregate</li>
                </ol>
            </nav>
        </div><!-- End Page Title -->

        <section class="section">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Filter Report</h5>

                            <form method="GET" action="{{ route('admin.reports.aggregate') }}">
                                <div class="row mb-3">
                                    <div class="col-md-5">
                                        <label for="from_date" class="form-label">From Date</label>
                                        <input type="date" class="form-control" id="from_date" name="from_date" value="{{ $fromDate }}">
                                    </div>
                                    <div class="col-md-5">
                                        <label for="to_date" class="form-label">To Date</label>
                                        <input type="date" class="form-control" id="to_date" name="to_date" value="{{ $toDate }}">
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
                            <h5 class="card-title">Referrals by Status</h5>
                            <p>Aggregated referral counts based on their status from <strong>{{ $fromDate }}</strong> to <strong>{{ $toDate }}</strong>.</p>
                            <a href="{{ route('admin.reports.aggregate', ['from_date' => $fromDate, 'to_date' => $toDate, 'export' => 'csv', 'report_type' => 'status']) }}" class="btn btn-success btn-sm mb-3">Export as CSV</a>

                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th scope="col">Status</th>
                                        <th scope="col">Count</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @forelse($referralsByStatus as $row)
                                    <tr>
                                        <td>{{ $row->fhir_status ?? 'N/A' }}</td>
                                        <td>{{ $row->count }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2">No data available for the selected period.</td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Referrals by Facility</h5>
                            <p>Aggregated referral counts based on the facility they were referred to from <strong>{{ $fromDate }}</strong> to <strong>{{ $toDate }}</strong>.</p>
                             <a href="{{ route('admin.reports.aggregate', ['from_date' => $fromDate, 'to_date' => $toDate, 'export' => 'csv', 'report_type' => 'facility']) }}" class="btn btn-success btn-sm mb-3">Export as CSV</a>

                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th scope="col">Facility</th>
                                        <th scope="col">Count</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @forelse($referralsByFacility as $row)
                                    <tr>
                                        <td>{{ $row->Officialname ?? 'N/A' }}</td>
                                        <td>{{ $row->count }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2">No data available for the selected period.</td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Referrals by Service</h5>
                             <p>Aggregated referral counts based on the service requested from <strong>{{ $fromDate }}</strong> to <strong>{{ $toDate }}</strong>.</p>
                            <a href="{{ route('admin.reports.aggregate', ['from_date' => $fromDate, 'to_date' => $toDate, 'export' => 'csv', 'report_type' => 'service']) }}" class="btn btn-success btn-sm mb-3">Export as CSV</a>

                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th scope="col">Service</th>
                                        <th scope="col">Count</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @forelse($referralsByService as $row)
                                    <tr>
                                        <td>{{ $row->service ?? 'N/A' }}</td>
                                        <td>{{ $row->count }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2">No data available for the selected period.</td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div>
        </section>

    </main><!-- End #main -->
@endsection 