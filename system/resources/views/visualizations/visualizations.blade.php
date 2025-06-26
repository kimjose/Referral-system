@extends('layouts.backend')

@section('content')
    <main id="main" class="main">

        <div class="pagetitle">
            <h1>Visualizations</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item">Reports</li>
                    <li class="breadcrumb-item active">Visualizations</li>
                </ol>
            </nav>
        </div><!-- End Page Title -->

        <section class="section dashboard">
            <div class="row">
                <div class="col-lg-12">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Referral Status <span>/Last 7 Days</span></h5>
                                    <div style="width: 100%;">{!! $chart->container() !!}</div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Completed vs Pending Referrals <span>/Past Month</span></h5>
                                    <div style="width: 100%;">{!! $completedPieChart->container() !!}</div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Accepted vs Pending vs Rejected Referrals <span>/Past Month</span></h5>
                                    <div style="width: 50%">{!! $completedPieChart2->container() !!}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    {!! $chart->script() !!}
    {!! $completedPieChart->script() !!}
    {!! $completedPieChart2->script() !!}
@endsection


