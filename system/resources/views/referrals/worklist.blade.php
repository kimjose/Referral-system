@extends('layouts.backend')
<style>
    .form-floating>.form-control,
    .form-floating>.form-control-plaintext {
        padding: 0rem 0.75rem !important;
    }

    .form-floating>.form-control,
    .form-floating>.form-control-plaintext,
    .form-floating>.form-select {
        height: calc(2.5rem + 2px) !important;
        line-height: 1 !important;
    }

    .form-floating>label {
        padding: 0.5rem 0.75rem !important;
    }
</style>
@section('content')

    <main id="main" class="main">

        @if(session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @endif

        <div class="pagetitle">
            <h1 class="mb-2">Patients List</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">Worklist</li>
                    <li class="breadcrumb-item active">All Patients</li>
                </ol>
            </nav>
        </div><!-- End Page Title -->

        <section class="section dashboard">
            <div class="field">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped datatable" id="patientTable">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>ID Number</th>
                                <th>Gender</th>
                                <th>Telephone</th>
                                <th class="no-sort text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Patient records will be dynamically added here -->
                            @foreach ($patients as $patient)
                                {{-- <tr onclick="window.location.href='{{ route('referral.tabs', ['tab' => " tab1"]) }}'">
                                    --}}
                                <tr
                                    onclick="window.location.href='{{ route('referral.tabs', ['tab' => 'tab1']) }}?patientId={{ $patient->id }}'">
                                    <td>{{ $patient->id }}</td>
                                    <td>{{ $patient->first_name }}</td>
                                    <td>{{ $patient->last_name }}</td>
                                    <td>{{ $patient->idNo }}</td>
                                    <td>{{ $patient->gender }}</td>
                                    <td>{{ $patient->telephone }}</td>
                                    <td class="refer-btn text-center" data-patient-id="{{ $patient->id }}">
                                        <div class="btn-group" role="group">
                                            <button type="" class="btn btn-update" data-bs-toggle="dropdown"
                                                aria-expanded="false">
                                                View Patient
                                            </button>
                                        </div>
                                    </td>
                                    <!-- Add more patient fields as needed -->
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </section>

    </main>

    {{-- SCRIPT SECTION --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


    <script>
        $(document).ready(function () {
            // Handle click event
            $('.refer-btn').on('click', function () {
                console.log("clicked")
                // Get the patient ID from the data attribute
                var patientId = $(this).data('patient-id');
                var url = '{{ route('referral.tabs', ['tab' => 'tab1']) }}';
                url += '?patientId=' + encodeURIComponent(patientId);

                console.log(url)

            });

            $('#patientTable').DataTable({
                columnDefs: [
                    { targets: 'no-sort', orderable: false }
                ]
            });
        });
    </script>


@endsection