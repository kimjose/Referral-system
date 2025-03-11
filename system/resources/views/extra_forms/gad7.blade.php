@extends('layouts.backend')

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://code.jquery.com/jquery-3.7.0.slim.min.js"></script>
{{--<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>--}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.8/js/select2.min.js" defer></script>


@section('form')

<main id="main" class="main">
    <div class="pagetitle">
        <h1>GAD 7</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Patient</a></li>
                <li class="breadcrumb-item active">GAD7</li>
            </ol>
        </nav>
    </div>

    <div class="container py-5">
        <div class="card">
            <div class="card-body p-4">
                <div class="pagetitle">
                    <h1 class="text-center mb-5">Generalised Anxiety Disorder(GAD7) Form</h1>
                </div>
                <form id="phq9" method="post" action="{{ route('gad7.storeGad7') }}">
                    @method("POST")
                    @csrf
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name" class="form-label">Name:</label>
                                <select class="form-control selected2" id="selectedPatient" name="patient_id">
                                    <option selected disabled style="text-align: center !important;">-- search patient
                                        --
                                    </option>
                                    @foreach($patients as $patient)
                                    <option  value="{{$patient->id}}" patient="{{$patient}}">{{$patient->idNo}} - {{$patient->first_name.'
                                        '.$patient->last_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">

                            <div class="form-group">
                                <label for="age" class="form-label">Age:</label>
                                <input type="text" readonly class="form-control readonly" id="age" name="age" min="1"
                                    required>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="gender" class="form-label">Gender:</label>
                            <input type="text" readonly class="form-control" id="gender" name="gender" min="1" required
                                placeholder="-- gender --">

                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="assessment_date">Assessment Date</label>
                                <input type="date" name="assessment_date" id="assessment_date" class="form-control"
                                    max="{{$today}}" value="{{$today}}">
                            </div>
                        </div>
                    </div>
                    <h5>Over the last two weeks, how often have you been bothered by any of the following problems</h5>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="anxious_nervous" class="form-label">Feeling nervous, anxious, or on edge</label>
                            <select class="form-control selected2" id="anxious_nervous" name="anxious_nervous">
                                <option selected disabled style="text-align: center !important;">-- Select Option --
                                </option>
                                @foreach($assessmentScores as $assessmentScore)
                                <option value="{{$assessmentScore['value']}}">{{$assessmentScore['name']}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="uncontrollable_worrying" class="form-label">Not being able to stop or control
                                worrying</label>
                            <select class="form-control selected2" id="uncontrollable_worrying" name="uncontrollable_worrying">
                                <option selected disabled style="text-align: center !important;">-- Select Option --
                                </option>
                                @foreach($assessmentScores as $assessmentScore)
                                <option value="{{$assessmentScore['value']}}">{{$assessmentScore['name']}}</option>
                                @endforeach
                            </select>
                        </div>

                    </div>

                    <div class="row mb-3">

                        <div class="col-md-6">
                            <label for="worrying_too_much" class="form-label">Worrying too much about different things</label>
                            <select class="form-control selected2" id="worrying_too_much" name="worrying_too_much">
                                <option selected disabled style="text-align: center !important;">-- Select Option --
                                </option>
                                @foreach($assessmentScores as $assessmentScore)
                                <option value="{{$assessmentScore['value']}}">{{$assessmentScore['name']}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="trouble_relaxing" class="form-label">Trouble relaxing</label>
                            <select class="form-control selected2" id="trouble_relaxing" name="trouble_relaxing">
                                <option selected disabled style="text-align: center !important;">-- Select Option --
                                </option>
                                @foreach($assessmentScores as $assessmentScore)
                                <option value="{{$assessmentScore['value']}}">{{$assessmentScore['name']}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3">

                        <div class="col-md-6">
                            <label for="restless" class="form-label">Being so restless that it is hard to sit still</label>
                            <select class="form-control selected2" id="restless" name="restless">
                                <option selected disabled style="text-align: center !important;">-- Select Option --
                                </option>
                                @foreach($assessmentScores as $assessmentScore)
                                <option value="{{$assessmentScore['value']}}">{{$assessmentScore['name']}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="irritable" class="form-label">Becoming easily annoyed or irritable</label>
                            <select class="form-control selected2" id="irritable"
                                name="irritable">
                                <option selected disabled style="text-align: center !important;">-- Select Option --
                                </option>
                                @foreach($assessmentScores as $assessmentScore)
                                <option value="{{$assessmentScore['value']}}">{{$assessmentScore['name']}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3">

                        <div class="col-md-6">
                            <label for="afraid" class="form-label">Feeling afraid, as if something awful might happen</label>
                            <select class="form-control selected2" id="afraid"
                                name="afraid">
                                <option selected disabled style="text-align: center !important;">-- Select Option --
                                </option>
                                @foreach($assessmentScores as $assessmentScore)
                                <option value="{{$assessmentScore['value']}}">{{$assessmentScore['name']}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>


                    <input type="submit" value="Submit" class="btn btn-primary">
                    
                </form>

            </div>
        </div>
    </div>
</main>
@endsection
<script>
    $(document).ready(function() {
        $('.selected2').select2();
    });

    $(document).ready(function() {
        $('#selectedPatient').on('change', function() {

            var selectedPatient = $(this).find('option:selected').attr('patient');

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
    });

</script>