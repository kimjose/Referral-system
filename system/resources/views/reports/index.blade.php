@extends('layouts.backend')

@section('content')
<main id="main" class="main">

    <div class="pagetitle">
        <h1>Reports Hub</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Reports</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="row">
            <!-- Dashboards & Visualizations -->
            <div class="col-12">
                <div class="card rounded-3 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title text-primary">Dashboards & Visualizations</h5>
                        <div class="row">
                            <div class="col-md-6 col-lg-4 mb-3">
                                <div class="card h-100 border-light">
                                    <div class="card-body d-flex flex-column">
                                        <h6 class="card-subtitle my-3 text-dark">Data Visualizations</h6>
                                        <p class="card-text small text-muted">An interactive dashboard with charts and graphs summarizing referral data.</p>
                                        <a href="{{ route('admin.dashboard.charts') }}" class="btn btn-outline-primary mt-auto">View Visualizations</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Aggregate Reports -->
            <div class="col-12 mt-4">
                <div class="card rounded-3 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title text-primary">Aggregate Reports</h5>
                        <div class="row">
                            <div class="col-md-6 col-lg-4 mb-3">
                                <div class="card h-100 border-light">
                                    <div class="card-body d-flex flex-column">
                                        <h6 class="card-subtitle my-3 text-dark">Aggregate Summary</h6>
                                        <p class="card-text small text-muted">Summarizes referral counts by status, facility, and service within a date range.</p>
                                        <a href="{{ route('admin.reports.aggregate') }}" class="btn btn-outline-primary mt-auto">View Report</a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-4 mb-3">
                                <div class="card h-100 border-light">
                                    <div class="card-body d-flex flex-column">
                                        <h6 class="card-subtitle my-3 text-dark">Turnaround Time (TAT)</h6>
                                        <p class="card-text small text-muted">Measures the time taken to process referrals, grouped by facility and service.</p>
                                        <a href="{{ route('admin.reports.turnaround-time') }}" class="btn btn-outline-primary mt-auto">View Report</a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-4 mb-3">
                                <div class="card h-100 border-light">
                                    <div class="card-body d-flex flex-column">
                                        <h6 class="card-subtitle my-3 text-dark">Referring Facility</h6>
                                        <p class="card-text small text-muted">Shows the total number of referrals sent from each facility.</p>
                                        <a href="{{ route('admin.reports.referring-facility') }}" class="btn btn-outline-primary mt-auto">View Report</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Disaggregate & Line List Reports -->
            <div class="col-12 mt-4">
                <div class="card rounded-3 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title text-primary">Line Lists & Disaggregate Reports</h5>
                        <div class="row">
                            <div class="col-md-6 col-lg-4 mb-3">
                                <div class="card h-100 border-light">
                                    <div class="card-body d-flex flex-column">
                                        <h6 class="card-subtitle my-3 text-dark">All Referrals (Disaggregate)</h6>
                                        <p class="card-text small text-muted">A comprehensive, filterable list of all referrals in the system.</p>
                                        <a href="{{ route('admin.reports.disaggregate') }}" class="btn btn-outline-primary mt-auto">View Report</a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-4 mb-3">
                                <div class="card h-100 border-light">
                                    <div class="card-body d-flex flex-column">
                                        <h6 class="card-subtitle my-3 text-dark">Pending Referrals</h6>
                                        <p class="card-text small text-muted">A line list of all referrals currently with a 'Pending' status.</p>
                                        <a href="{{ route('admin.reports.linelist', 'Pending') }}" class="btn btn-outline-primary mt-auto">View List</a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-4 mb-3">
                                <div class="card h-100 border-light">
                                    <div class="card-body d-flex flex-column">
                                        <h6 class="card-subtitle my-3 text-dark">Accepted Referrals</h6>
                                        <p class="card-text small text-muted">A line list of all referrals that have been accepted.</p>
                                        <a href="{{ route('admin.reports.linelist', 'Accepted') }}" class="btn btn-outline-primary mt-auto">View List</a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-4 mb-3">
                                <div class="card h-100 border-light">
                                    <div class="card-body d-flex flex-column">
                                        <h6 class="card-subtitle my-3 text-dark">Rejected Referrals</h6>
                                        <p class="card-text small text-muted">A line list of all referrals that have been rejected.</p>
                                        <a href="{{ route('admin.reports.linelist', 'Rejected') }}" class="btn btn-outline-primary mt-auto">View List</a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-4 mb-3">
                                <div class="card h-100 border-light">
                                    <div class="card-body d-flex flex-column">
                                        <h6 class="card-subtitle my-3 text-dark">Incoming Referrals</h6>
                                        <p class="card-text small text-muted">A line list of all referrals received by your facility.</p>
                                        <a href="{{ route('reports.incoming') }}" class="btn btn-outline-primary mt-auto">View List</a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-4 mb-3">
                                <div class="card h-100 border-light">
                                    <div class="card-body d-flex flex-column">
                                        <h6 class="card-subtitle my-3 text-dark">Outgoing Referrals</h6>
                                        <p class="card-text small text-muted">A line list of all referrals sent from your facility.</p>
                                        <a href="{{ route('reports.outgoing') }}" class="btn btn-outline-primary mt-auto">View List</a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-4 mb-3">
                                <div class="card h-100 border-light">
                                    <div class="card-body d-flex flex-column">
                                        <h6 class="card-subtitle my-3 text-dark">Completed Referrals</h6>
                                        <p class="card-text small text-muted">A line list of all referrals that are either 'Accepted' or 'Rejected'.</p>
                                        <a href="{{ route('reports.completed') }}" class="btn btn-outline-primary mt-auto">View List</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

</main><!-- End #main -->
@endsection 