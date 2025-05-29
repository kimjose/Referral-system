@extends('layouts.backend')

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://code.jquery.com/jquery-3.7.0.slim.min.js"></script>
{{--
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>--}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.8/js/select2.min.js" defer></script>


@section('form')

    <main id="main" class="main">
        <div class="pagetitle">
            <h1 class="mb-2">PC PTSD-5: Post Traumatic stress disorder</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Patient</a></li>
                    <li class="breadcrumb-item active">PTSD-5</li>
                </ol>
            </nav>
        </div>

        <div class="container">
            <div class="main-body">
                <form id="ptsd5" method="post" action="{{ route('ptsd5.storePtsd5') }}">
                    @method("POST")
                    @csrf
                    <div class="row mb-3">
                        <div class="field pt-3">
                            <h5 class="title-x">Patient Details</h5>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="name" class="form-label">Patient Name:</label>
                                        <select class="form-control selected2" id="selectedPatient" name="patient_id">
                                            <option selected disabled style="text-align: center !important;">-- search
                                                patient
                                                --
                                            </option>
                                            @foreach($patients as $patient)
                                                <option value="{{$patient->id}}" patient="{{$patient}}">{{$patient->idNo}} - {{$patient->first_name . '
                                                                                ' . $patient->last_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">

                                    <div class="form-group">
                                        <label for="age" class="form-label">Age:</label>
                                        <input type="text" readonly class="form-control readonly" id="age" name="age"
                                            min="1" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="gender" class="form-label">Gender:</label>
                                    <input type="text" readonly class="form-control" id="gender" name="gender" min="1"
                                        required placeholder="-- gender --">

                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="assessment_date">Assessment Date</label>
                                        <input type="date" name="assessment_date" id="assessment_date" class="form-control"
                                            max="{{$today}}" value="{{$today}}">
                                    </div>
                                </div>
                            </div>
                            <p>Sometimes things happen to people that are unusually or especially frightening, horrible, or
                                traumatic. For example:</p>

                            <ol>
                                <li>a serious accident or fire</li>
                                <li>a physical or sexual assault or abuse</li>
                                <li>an earthquake or flood</li>
                                <li>a war</li>
                                <li>seeing someone be killed or seriously injured</li>
                                <li>having a loved one die through homicide or suicide.</li>
                            </ol>
                            <hr>
                            <div class="form-group">
                                <label for="experienced_trauma" class="form-label">Have you ever experienced this kind of
                                    event?</label>
                                <select class="form-control selected2" id="experienced_trauma" name="experienced_trauma">
                                    <option selected disabled style="text-align: center !important;">-- Select Option --
                                    </option>
                                    <option value="Yes">Yes</option>
                                    <option value="No">No</option>
                                </select>
                            </div>
                            <div id="divTrauma" class="d-none">
                                <h5>In the past month, have you…</h5>
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="nightmares" class="form-label">had nightmares about the event(s) or
                                            thought
                                            about the event(s) when you did not want to?</label>
                                        <select class="form-control selected2" id="nightmares" name="nightmares">
                                            <option selected disabled style="text-align: center !important;">-- Select
                                                Option --
                                            </option>
                                            <option value="Yes">Yes</option>
                                            <option value="No">No</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="hard_not_to_think" class="form-label">tried hard not to think about the
                                            event(s) or went out of your way to avoid situations that reminded you of the
                                            event(s)?</label>
                                        <select class="form-control selected2" id="hard_not_to_think"
                                            name="hard_not_to_think">
                                            <option selected disabled style="text-align: center !important;">-- Select
                                                Option --
                                            </option>
                                            <option value="Yes">Yes</option>
                                            <option value="No">No</option>
                                        </select>
                                    </div>

                                </div>

                                <div class="row mb-3">

                                    <div class="col-md-6">
                                        <label for="on_guard" class="form-label">been constantly on guard, watchful, or
                                            easily
                                            startled?</label>
                                        <select class="form-control selected2" id="on_guard" name="on_guard">
                                            <option selected disabled style="text-align: center !important;">-- Select
                                                Option --
                                            </option>
                                            <option value="Yes">Yes</option>
                                            <option value="No">No</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="felt_numb" class="form-label">felt numb or detached from people,
                                            activities,
                                            or your surroundings?</label>
                                        <select class="form-control selected2" id="felt_numb" name="felt_numb">
                                            <option selected disabled style="text-align: center !important;">-- Select
                                                Option --
                                            </option>
                                            <option value="Yes">Yes</option>
                                            <option value="No">No</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="row mb-3">

                                    <div class="col-md-6">
                                        <label for="felt_guilty" class="form-label">felt guilty or unable to stop blaming
                                            yourself or others for the event(s) or any problems the event(s) may have
                                            caused?</label>
                                        <select class="form-control selected2" id="felt_guilty" name="felt_guilty">
                                            <option selected disabled style="text-align: center !important;">-- Select
                                                Option --
                                            </option>
                                            <option value="Yes">Yes</option>
                                            <option value="No">No</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <input type="submit" value="Submit" class="btn btn-update">

                </form>
            </div>
        </div>
    </main>
@endsection
<script>
    $(document).ready(function () {
        $('.selected2').select2({
            width: '100%'
        });
    });

    $(document).ready(function () {
        $('#selectedPatient').on('change', function () {

            let selectedPatient = $(this).find('option:selected').attr('patient');

            if (selectedPatient !== '') {
                var patient = JSON.parse(selectedPatient);

                var dob = patient.dob
                // Split the date and time parts
                var parts = dob.split(' ');
                var datePart = parts[0]; // "2007-04-15"

                // Split the date into year, month, and day
                var dateParts = datePart.split('-');
                var year = parseInt(dateParts[0], 10);
                var month = parseInt(dateParts[1], 10) - 1; // Month is zero-based in JavaScript
                var day = parseInt(dateParts[2], 10);

                // Calculate the age
                var birthDate = new Date(year, month, day);
                var currentDate = new Date();
                var ageDiff = currentDate - birthDate;

                // Convert the age difference to years
                var ageDate = new Date(ageDiff);
                var age = Math.abs(ageDate.getUTCFullYear() - 1970);

                // var date = Carbon

                //console.log(age)


                $('#age').val(age);
                $('#gender').val(patient.gender);
            } else {
                // Clear the fields if no object is selected
                $('#age').val('-- age --');
                $('#gender').val('-- gender --');
                //$('#field2').val('');
            }
        });

        $('#experienced_trauma').on('change', function () {
            let selected = $(this).val();
            let divTrauma = document.querySelector("#divTrauma")
            if (selected === 'Yes') {
                divTrauma.classList.remove('d-none')
                let inputs = divTrauma.querySelectorAll('select')
                inputs.forEach(input => {
                    input.setAttribute('required', '')
                })
            } else {
                divTrauma.classList.add('d-none')
                let inputs = divTrauma.querySelectorAll('select')
                inputs.forEach(input => {
                    input.removeAttribute('required')
                })
            }
        })

    });


</script>