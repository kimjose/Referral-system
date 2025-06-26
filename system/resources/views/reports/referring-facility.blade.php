@extends('layouts.backend')
@section('content')
<main id="main" class="main">

    <div class="pagetitle">
        <h1>Referring Facility Report</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item">Reports</li>
                <li class="breadcrumb-item active">Referring Facility Report</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Filter Report</h5>
                        <p>This report shows the total number of referrals sent by each facility within a specified date range.</p>
                        <form method="GET" action="{{ route('admin.reports.referring-facility') }}">
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
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="card-title">Report Results</h5>
                            <a href="{{ route('admin.reports.referring-facility', ['from_date' => $fromDate, 'to_date' => $toDate, 'export' => 'csv']) }}" class="btn btn-success btn-sm">Export as CSV</a>
                        </div>
                        
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th scope="col">Referring Facility</th>
                                    <th scope="col">Total Referrals Sent</th>
                                </tr>
                            </thead>
                            <tbody>
                            @forelse($reportData as $row)
                                <tr>
                                    <td>{{ $row->Officialname }}</td>
                                    <td>{{ $row->count }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" class="text-center">No data found for the selected period.</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>

</main>
@endsection 