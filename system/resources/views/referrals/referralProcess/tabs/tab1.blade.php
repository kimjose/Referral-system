<!-- tab1.blade.php -->
@extends('referrals.referralProcess.layout.referral-tabs-layout')

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://code.jquery.com/jquery-3.7.0.slim.min.js"></script>
{{--
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>--}}
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>


@section('tab-content')
    <div class="tab-pane {{ $activeTab === 'tab1' ? 'active' : '' }}" id="tab1" role="tabpanel">
        <div class="accordion-item">
            {{-- <h2 class="accordion-header" id="headingOne">--}}
                {{-- <h1 class="card-title" style="font-size: 180% !important;">Patient Details</h1>--}}
                {{-- </h2>--}}
            <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne"
                data-bs-parent="#accordionExample">
                <div class="accordion-body" style="background: #fff;">
                    <div class="row">
                        <div class="col-4 border-end ps-4 pb-3">
                            <h4 class="card-title">Patient Details</h4>
                            <p><strong>Full Name</strong> {{ $patient->first_name }} {{ $patient->last_name }}
                            </p>
                            <p><strong>Identification Number:</strong> {{ $patient->idNo }}</p>
                            <p><strong>Age:</strong> {{ \Carbon\Carbon::parse($patient->dob)->age }}</p>
                            <p><strong>UPI Number:</strong> {{ $patient->upi }}</p>
                            <p><strong>Telephone number:</strong> {{ $patient->telephone }}</p>
                            <p><strong>County:</strong> {{ $patient->countyOfBirth }} County</p>
                            <p><strong>Sub County:</strong> {{ $patient->subCounty }}</p>
                            <p><strong>Constituency:</strong> {{ $patient->village }}</p>
                            <p><strong>Ward:</strong> {{ $patient->address }}</p>
                            <p><strong>Phone:</strong> {{ $patient->telephone }}</p>


                            <button id="refer_button" class="btn btn-update">Refer Patient<i
                                    class="fas fa-arrow-right ms-2"></i></button>
                        </div>

                        <div class="col-4 border-end pb-3">
                            <h6 class="card-title">Next of Kin Details</h6>
                            <p><strong>Full name:</strong> {{ $patient->kinName }} {{ $patient->last_name }}</p>
                            <p><strong>Relationship:</strong> {{ $patient->relationship }}</p>
                            <p><strong>Phone:</strong> {{ $patient->kinTelephone }}</p>
                        </div>

                        <div class="col-4 pe-4 pb-3">
                            <h6 class="card-title">Medical History</h6>
                            <div class="pb-3">
                                <label for="surgeries" class="pb-2">Have you had any surgeries?<span class="required"
                                        style="color: red">*</span></label>
                                <select id="surgeries" name="surgeries" class="form-control" required>
                                    <option selected disabled>--- Select one ---</option>
                                    <option value="yes">Yes</option>
                                    <option value="no">No</option>
                                </select>
                            </div>
                            <div class="pb-3">
                                <label for="allergies" class="pb-2">Do you have any allergies?<span class="required"
                                        style="color: red">*</span></label>
                                <select id="allergies" name="allergies" class="form-control" required>
                                    <option selected disabled>--- Select one ---</option>
                                    <option value="yes">Yes</option>
                                    <option value="no">No</option>
                                </select>
                            </div>
                            <div class="pb-3">
                                <label for="medication" class="pb-2">Do you take any medication?<span class="required"
                                        style="color: red">*</span></label>
                                <select id="medication" name="medication" class="form-control" required>
                                    <option selected disabled>--- Select one ---</option>
                                    <option value="yes">Yes</option>
                                    <option value="no">No</option>
                                </select>
                            </div>
                            <div class="pb-3">
                                <label for="other_medical_conditions" class="pb-2">List any other medical conditions or
                                    diagnoses you have</label>
                                <textarea class="form-control" id="other_medical_conditions"
                                    name="other_medical_conditions"></textarea>
                            </div>
                            <button type="submit" class="btn btn-update">Update medical history</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>


    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script>

        $(document).ready(function () {
            var userId = {{ auth()->id() }};
            var patient = {!! json_encode($patient) !!};
            var patientName = patient.first_name + " " + patient.last_name;
            var patientUPI = patient.upi;
            console.log(userId);
            console.log(patientName);

            $('#refer_button').on('click', function () {
                // Send the data to the controller using AJAX POST request
                console.log("refer btn clicked")

                var url = '{{ route('referral.tabs', ['tab' => 'tab2']) }}';
                url += '?patientId=' + patient.id;
                { { --window.location.href = '{{ route('referral.tabs', ['tab' => 'tab2']) }}'; --} }
                window.location.href = url;
            });

        });




    </script>

@endsection