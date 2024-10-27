@extends('layouts.backend')

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://code.jquery.com/jquery-3.7.0.slim.min.js"></script>
{{--<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>--}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.8/js/select2.min.js" defer></script>


@section('form')

<main id="main" class="main">
    <div class="pagetitle">
        <h1>Patient Health Questionnaire (PHQ-9)-Depression</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Patient</a></li>
                <li class="breadcrumb-item active">PHQ9</li>
            </ol>
        </nav>
    </div>

    <div class="container py-5">
        <div class="card">
            <div class="card-body p-4">
                <div class="pagetitle">
                    <h1 class="text-center mb-5">Patient Health Questionnaire (PHQ-9)-Depression Form</h1>
                </div>
                <form id="phq9" method="post" action="{{ route('phq9.storeAssessment') }}">
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
                                    <option value="{{$patient}}">{{$patient->idNo}} - {{$patient->first_name.'
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
                            <label for="little_interest" class="form-label">Little interest or pleasure in doing
                                things</label>
                            <select class="form-control selected2" id="little_interest" name="little_interest">
                                <option selected disabled style="text-align: center !important;">-- Select Option --
                                </option>
                                @foreach($assessmentScores as $assessmentScore)
                                <option value="{{$assessmentScore['value']}}">{{$assessmentScore['name']}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="feeling_down" class="form-label">Feeling down, depress or helplessness</label>
                            <select class="form-control selected2" id="feeling_down" name="feeling_down">
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
                            <label for="trouble_sleeping" class="form-label">Trouble falling asleep, staying asleep
                                or not sleeping at all</label>
                            <select class="form-control selected2" id="trouble_sleeping" name="trouble_sleeping">
                                <option selected disabled style="text-align: center !important;">-- Select Option --
                                </option>
                                @foreach($assessmentScores as $assessmentScore)
                                <option value="{{$assessmentScore['value']}}">{{$assessmentScore['name']}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="feeling_tired" class="form-label"> Feeling tired or having little energy
                            </label>
                            <select class="form-control selected2" id="feeling_tired" name="feeling_tired">
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
                            <label for="poor_appetite" class="form-label">Poor appetite or overeating</label>
                            <select class="form-control selected2" id="poor_appetite" name="poor_appetite">
                                <option selected disabled style="text-align: center !important;">-- Select Option --
                                </option>
                                @foreach($assessmentScores as $assessmentScore)
                                <option value="{{$assessmentScore['value']}}">{{$assessmentScore['name']}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="feeling_bad_about_self" class="form-label">Feeling bad about yourself or that
                                you
                                are a failure or have let yourself down
                                or your family down</label>
                            <select class="form-control selected2" id="feeling_bad_about_self"
                                name="feeling_bad_about_self">
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
                            <label for="trouble_concentrating" class="form-label">Trouble concentrating on things
                                such as reading the newspaper or
                                watching television</label>
                            <select class="form-control selected2" id="trouble_concentrating"
                                name="trouble_concentrating">
                                <option selected disabled style="text-align: center !important;">-- Select Option --
                                </option>
                                @foreach($assessmentScores as $assessmentScore)
                                <option value="{{$assessmentScore['value']}}">{{$assessmentScore['name']}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="slow_or_fidgety" class="form-label">Moving or speaking so slowly that
                                other people could have noticed.
                                Or the opposite being so fidgety or
                                restless that you are moving a lot
                                more than usual</label>
                            <select class="form-control selected2" id="slow_or_fidgety" name="slow_or_fidgety">
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
                            <label for="thoughts_of_self_hurt" class="form-label">Thoughts that you would be better
                                dead or of hurting yourself</label>
                            <select class="form-control selected2" id="thoughts_of_self_hurt"
                                name="thoughts_of_self_hurt">
                                <option selected disabled style="text-align: center !important;">-- Select Option --
                                </option>
                                @foreach($assessmentScores as $assessmentScore)
                                <option value="{{$assessmentScore['value']}}">{{$assessmentScore['name']}}</option>
                                @endforeach
                            </select>
                        </div>

                    </div>

                    <div class="row mb-3">
                        <div class="col-12">
                            <label for="difficult_caused" class="form-label">If you checked any problems, how difficult
                                have
                                they made it for you to do your work, take
                                care of things at home, or get along with other people?</label>
                            <select class="form-control selected2" id="difficult_caused" name="difficult_caused">
                                <option selected disabled style="text-align: center !important;">-- Select Option --
                                </option>
                                <option value="Not difficult at all">Not difficult at all</option>
                                <option value="Somewhat difficult">Somewhat difficult</option>
                                <option value="Very difficult">Very difficult</option>
                                <option value="Extremely difficult">Extremely difficult</option>
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

            var selectedPatient = $(this).val();//this is a jquery function for getting the selected object

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