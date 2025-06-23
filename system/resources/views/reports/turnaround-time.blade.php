@extends('layouts.backend')
@section('content')
<main id="main" class="main">

    <div class="pagetitle">
        <h1>Referral Turnaround Time (TAT) Report</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item">Reports</li>
                <li class="breadcrumb-item active">Turnaround Time</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Filter Report</h5>
                        <p>This report calculates the time from when a referral is created until it is either 'Accepted' or 'Rejected'.</p>

                        <form method="GET" action="{{ route('admin.reports.turnaround-time') }}">
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
                        <h5 class="card-title">Turnaround Time by Facility</h5>
                        
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th scope="col">Facility</th>
                                    <th scope="col">Total Referrals</th>
                                    <th scope="col">Avg. TAT</th>
                                    <th scope="col">Min. TAT</th>
                                    <th scope="col">Max. TAT</th>
                                </tr>
                            </thead>
                            <tbody>
                            @forelse($tatByFacility as $row)
                                <tr>
                                    <td>{{ $row->Officialname }}</td>
                                    <td>{{ $row->count }}</td>
                                    <td>{{ $row->avg_tat_formatted }}</td>
                                    <td>{{ $row->min_tat_formatted }}</td>
                                    <td>{{ $row->max_tat_formatted }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">No completed referrals found for the selected period.</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Turnaround Time by Service</h5>

                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th scope="col">Service</th>
                                    <th scope="col">Total Referrals</th>
                                    <th scope="col">Avg. TAT</th>
                                    <th scope="col">Min. TAT</th>
                                    <th scope="col">Max. TAT</th>
                                </tr>
                            </thead>
                            <tbody>
                            @forelse($tatByService as $row)
                                <tr>
                                    <td>{{ $row->service }}</td>
                                    <td>{{ $row->count }}</td>
                                    <td>{{ $row->avg_tat_formatted }}</td>
                                    <td>{{ $row->min_tat_formatted }}</td>
                                    <td>{{ $row->max_tat_formatted }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">No completed referrals found for the selected period.</td>
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