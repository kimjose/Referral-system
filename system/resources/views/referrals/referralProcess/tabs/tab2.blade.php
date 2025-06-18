<!-- tab2.blade.php -->
@extends('referrals.referralProcess.layout.referral-tabs-layout')
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js" defer></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />


@section('tab-content')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <div class="tab-pane {{ $activeTab === 'tab2' ? 'active' : '' }}" id="tab2" role="tabpanel">
        <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne"
            data-bs-parent="#accordionExample">
            <div class="accordion-body" style="background: #fff;">
                <div class="card px-4 pb-3">
                    <h6 class="card-title">Referral Details</h6>
                    <form action="{{route('referral.tabs.save', ['tab' => 'tab2'])}}" method="POST">
                        @csrf
                        <input type="hidden" id="user_id" name="user_id" value="{{ auth()->id() }}">
                        <input type="hidden" id="patient_id" name="patientId" value="{{ $_GET['patientId'] ?? '' }}">
                        <div class="pb-2">
                            <label for="referringOfficer">Referring Officer</label>
                            <input type='text' class="form-control mt-2" name='referringOfficer' id='referringOfficer'
                                placeholder="Referring Officer's Name" value="{{ Auth::user()->name }}" readonly>
                        </div>
                        <div class=" pb-2">
                            <label for="historyInvestigation" class="mb-2">History/Investigation<span class="required"
                                    style="color: red">*</span></label>
                            <select id="historyInvestigation" name="historyInvestigation" class="form-control" required>
                                <option>--- Select History/Investigation Facility ---</option>
                                <!-- Place all the options here initially -->
                                @foreach($diagnosis as $reason)
                                    <option value="{{ $reason->id }}">{{ $reason->{'from concept name'} }}</option>
                                @endforeach
                            </select>
                            <script>
                                $(document).ready(function () {
                                    // Initialize Select2
                                    $('#historyInvestigation').select2({
                                        placeholder: 'Type to search...',
                                        minimumInputLength: 1 // Minimum number of characters to trigger the autocomplete
                                    });
                                });
                            </script>
                        </div>
                        <div class=" pb-2">
                            <label for="diagnosis" class="mb-2">Diagnosis<span class="required"
                                    style="color: red">*</span></label>
                            <select id="diagnosis" name="diagnosis" class="form-control" required>
                                <option>--- Select Diagnosis details ---</option>
                                <!-- Populate diagnosis options dynamically using coded concepts -->
                                @foreach($diagnosis as $reason)
                                    <option value="{{ $reason->id }}">{{ $reason->{'from concept name'} }}</option>
                                @endforeach
                            </select>
                            <script>
                                $(document).ready(function () {
                                    // Initialize Select2
                                    $('#diagnosis').select2({
                                        placeholder: 'Type to search...',
                                        minimumInputLength: 1 // Minimum number of characters to trigger the autocomplete
                                    });
                                });
                            </script>
                        </div>
                        <div class=" pb-2">
                            <label for="reasonReferral" class="mb-2">Reason for Referral<span class="required"
                                    style="color: red">*</span></label>
                            <select id="reasonReferral" name="reasonReferral" class="form-control" required>

                                <option>--- Select reason for referral ---</option>
                                <option value="Equipment">Lack of equipment</option>
                                <option value="Expertise">Lack of expertise</option>
                                <option value="Out of scope Service">Service sought is out of scope</option>
                            </select>
                            <script>
                                $(document).ready(function () {
                                    // Initialize Select2
                                    $('#reasonReferral').select2({
                                        placeholder: 'Type to search...',
                                        minimumInputLength: 1 // Minimum number of characters to trigger the autocomplete
                                    });
                                });
                            </script>
                        </div>
                        <div class=" pb-2">
                            <label for="priorityLevel" class="mb-2">Priority Level<span class="required"
                                    style="color: red">*</span></label>
                            <select id="priorityLevel" name="priorityLevel" class="form-control">
                                <option value="">--- Select Priority Level ---</option>
                                <option value="emergency">Emergency</option>
                                <option value="routine">Routine</option>
                            </select>
                        </div>
                        <div class=" pb-2">
                            <label for="formFileMultiple" class="mb-2">Attachments</label>
                            <input type="file" class="form-control" id="formFileMultiple" multiple
                                placeholder='File Attachments'>
                        </div>
                        <div class=" pb-2">
                            <label for="additionalNotes" class="mb-2">Additional Notes</label>
                            <textarea cols="30" rows="10" class="form-control" id="additionalNotes" name="additionalNotes"
                                style="height: 100px;" placeholder='Additional Medical Notes' required></textarea>
                        </div>

                        <div class="mt-2">
                            <button type="reset" class="btn btn-angaza-secondary me-2">Reset</button>
                            <button id="next_button" type="submit" class="btn btn-update">Submit Details</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    {{-- SCRIPT SECTION --}}
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script>
        $(document).ready(function () {
            var userId = {{ auth()->id() }};
            var patient = {!! json_encode($patient) !!};
            var patientName = patient.first_name + " " + patient.last_name;
            var patientUPI = patient.upi;
/*
            $('#next_button').on('click', function () {
                // Send the data to the controller using AJAX POST request
                console.log("next btn clicked")
                // Get the form data
                var referringOfficer = $('#referringOfficer').val();
                var historyInvestigation = $('#historyInvestigation').val();
                var diagnosis = $('#diagnosis').val();
                var reasonReferral = $('#reasonReferral').val();
                var additionalNotes = $('#additionalNotes').val();
                var priorityLevel = $('#priorityLevel').val();

                // Create an object with the data
                var formData = {
                    referringOfficer: referringOfficer,
                    historyInvestigation: historyInvestigation,
                    diagnosis: diagnosis,
                    reasonReferral: reasonReferral,
                    additionalNotes: additionalNotes,
                    userId: userId,
                    clientName: patientName,
                    clientUPI: patientUPI,
                    priorityLevel: priorityLevel
                };


                $.ajax({
                    url: '{{ url('referral/tabs/save/tab2') }}',
                    type: 'POST',
                    data: formData,
                    success: function (response) {
                        console.log(response);
                        // Handle the response after saving
                        var referralId = response.referralId;
                        var referralSuccess = response.success;
                        var url = '{{ route('referral.tabs', ['tab' => 'tab3']) }}';
                        url += '?referralId=' + referralId;
                        window.location.href = url;
                    },

                    error: function (error) {
                        console.log(error);
                    }
                });

            });
            */
        });

    </script>

@endsection