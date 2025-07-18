@extends('layouts.backend')
<link href="{{ url('assets/css/patientProfile.css') }}" rel="stylesheet">

<link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

@section('content')


<main id="main" class="main">

    <div class="pagetitle">
        <h1> Patients </h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href=" ">Patients</a></li>
                <li class="breadcrumb-item active">Patient Profile</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <div class="container">
        <div class="main-body">

              <div class="row gutters-sm">
                <div class="col-md-4 mb-3">
                  <div class="card origami">
                    <div class="origami-body">
                      <div class="d-flex flex-column align-items-center text-center">
                        <img src="{{ url('assets/img/person-circle.svg') }}" alt="Admin" class="rounded-circle" width="150">
                        <div class="mt-3 pb-2">
                          <h4>{{ $patient->first_name }} {{ $patient->last_name }}</h4>
                          <p class="text-secondary mb-1">{{ $patient->upi }}</p>
                          <p class="text-muted font-size-sm">{{ $patient->village }}, {{ $patient->subCounty }}, {{ $patient->county }}, {{ $patient->country }}</p>
                          <button class="btn btn-primary" onclick="window.location.href='{{ route('referrals.createReferral', $patient) }}'">Refer Patient</button>
                          {{-- <button class="btn btn-outline-primary" onclick="window.location.href='{{ route('triages.addTriage2', $patient) }}'">Triage</button> --}}
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="card origami">
                    <div class="origami-body">
                        <div class="d-flex flex-column align-items-center text-center">
                            <h4>Referal History</h4>
                            <div class="mt-3 pb-2">
                                <table class="table table-bordered table-striped wide">
                                    <thead>
                                        <tr>
                                            <th>Referal ID</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>Null</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                  </div>
                </div>
                <div class="col-md-8">
                  <div class="card origami mb-3">
                    <div class="origami-body">
                        <h5 class="pb-1 font-weight-bold align-items-center text-center">Patient Details</h5>
                      <div class="row">
                        <div class="col-sm-3">
                          <h6 class="mb-0">Full Name</h6>
                        </div>
                        <div class="col-sm-9 text-secondary">
                            {{ $patient->first_name }} {{ $patient->last_name }}
                        </div>
                      </div>
                      <hr>
                      <div class="row">
                        <div class="col-sm-3">
                          <h6 class="mb-0">ID No</h6>
                        </div>
                        <div class="col-sm-9 text-secondary">
                            {{ $patient->idNo }}
                        </div>
                      </div>
                      <hr>
                      <div class="row">
                        <div class="col-sm-3">
                          <h6 class="mb-0">Contact</h6>
                        </div>
                        <div class="col-sm-9 text-secondary">
                            {{ $patient->telephone }}
                        </div>
                      </div>
                      <hr>
                      <div class="row">
                        <div class="col-sm-3">
                          <h6 class="mb-0">Age</h6>
                        </div>
                        <div class="col-sm-9 text-secondary">
                            {{ \Carbon\Carbon::parse($patient->dob)->age }}
                        </div>
                      </div>
                      <hr>
                      <div class="row">
                        <div class="col-sm-3">
                          <h6 class="mb-0">Address</h6>
                        </div>
                        <div class="col-sm-9 text-secondary">
                            {{ $patient->address }}
                        </div>
                      </div>
                      <hr>
                      <div class="row">
                        <div class="col-sm-3">
                          <h6 class="mb-0">Gender</h6>
                        </div>
                        <div class="col-sm-9 text-secondary">
                            {{ $patient->gender }}
                        </div>
                      </div>
                      {{-- <hr>
                      <div class="row">
                        <div class="col-sm-3">
                          <h6 class="mb-0">Gender</h6>
                        </div>
                        <div class="col-sm-9 text-secondary">
                            {{ $patient->gender }}
                        </div>
                      </div> --}}

                    </div>
                  </div>
                  <div class="card origami mb-3">
                        <div class="origami-body">
                            <h5 class="pb-1 font-weight-bold align-items-center text-center">Next of Kin</h5>
                            <div class="row">
                                <div class="col-sm-3">
                                <h6 class="mb-0">Name</h6>
                                </div>
                                <div class="col-sm-9 text-secondary">
                                    {{ $patient->kinName}}
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-sm-3">
                                <h6 class="mb-0">Relationship</h6>
                                </div>
                                <div class="col-sm-9 text-secondary">
                                    {{ $patient->relationship }}
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-sm-3">
                                <h6 class="mb-0">Residence</h6>
                                </div>
                                <div class="col-sm-9 text-secondary">
                                    {{ $patient->kinResidence }}
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-sm-3">
                                <h6 class="mb-0">Contact</h6>
                                </div>
                                <div class="col-sm-9 text-secondary">
                                    {{ $patient->kinTelephone }}
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-sm-3">
                                <h6 class="mb-0">email</h6>
                                </div>
                                <div class="col-sm-9 text-secondary">
                                    {{ $patient->mail }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Referral Journey Visualization -->
                    @php
                        $referrals = \App\Models\Referral::where('clientUPI', $patient->upi)->orderBy('created_at')->get();
                        $nodes = [];
                        $edges = [];
                        $lastFacility = null;
                        $facilityReferralCounts = [];
                        $hasReferrals = $referrals->count() > 0;
                        foreach ($referrals as $ref) {
                            $from = $ref->referring_facility_id ?: 'Unknown';
                            $to = $ref->referredFacility ?: 'Unknown';
                            $fromFacility = $ref->facilityReffering ? $ref->facilityReffering->Officialname : $from;
                            $toFacility = $ref->facilityReffered ? $ref->facilityReffered->Officialname : $to;
                            $nodes[$from] = $fromFacility;
                            $nodes[$to] = $toFacility;
                            $facilityReferralCounts[$from] = ($facilityReferralCounts[$from] ?? 0) + 1;
                            $facilityReferralCounts[$to] = ($facilityReferralCounts[$to] ?? 0) + 1;
                            $date = $ref->created_at ? $ref->created_at->format('Y-m-d') : '';
                            $status = strtolower($ref->fhir_status ?? $ref->status ?? '');
                            $edgeLabel = "$date | $status";
                            $edgeClass = $status === 'completed' ? 'completed' : ($status === 'pending' ? 'pending' : ($status === 'rejected' ? 'rejected' : 'other'));
                            $edges[] = [$from, $to, $edgeLabel, $edgeClass, $ref->id];
                            $lastFacility = $to;
                        }
                        $mermaid = "graph TD\n";
                        $mermaid .= "  Patient[\"Patient: {$patient->first_name} {$patient->last_name}\"]\n";
                        if ($hasReferrals) {
                            $first = $referrals->first();
                            $from = $first->referring_facility_id ?: 'Unknown';
                            $mermaid .= "  Patient --> {$from}\n";
                        }
                        foreach ($nodes as $code => $name) {
                            $count = $facilityReferralCounts[$code] ?? 1;
                            $mermaid .= "  {$code}[\"{$name}\"]:::facilityNode\n";
                        }
                        foreach ($edges as [$from, $to, $label, $class, $refId]) {
                            $mermaid .= "  {$from} --|{$label}| {$to}:::{$class}click {$from}_{$to}_{$refId} callShowReferralModal('{$refId}')\n";
                        }
                        if ($hasReferrals && strtolower($referrals->last()->fhir_status ?? $referrals->last()->status) === 'completed') {
                            $mermaid .= "  {$lastFacility} --> Community[\"Community/CHP\"]:::completed\n";
                        }
                        $mermaid .= "  classDef completed stroke:#28a745,stroke-width:3px,color:#28a745;\n";
                        $mermaid .= "  classDef pending stroke:#fd7e14,stroke-width:3px,color:#fd7e14;\n";
                        $mermaid .= "  classDef rejected stroke:#dc3545,stroke-width:3px,color:#dc3545;\n";
                        $mermaid .= "  classDef other stroke:#6c757d,stroke-width:2px,color:#6c757d;\n";
                        $mermaid .= "  classDef facilityNode fill:#f8f9fa,stroke:#007bff,stroke-width:2px;\n";
                    @endphp
                    <div class="col-md-8 mb-3">
                      <div class="card">
                        <div class="card-body">
                          <h5 class="card-title d-flex align-items-center">Referral Journey
                            <span class="ms-2" data-bs-toggle="tooltip" title="This diagram shows the patient's referral path. Click on arrows for details. Use the export button to download.">
                              <i class="bi bi-info-circle text-info"></i>
                            </span>
                            <button class="btn btn-sm btn-outline-secondary ms-auto" id="exportReferralTree" title="Export diagram"><i class="bi bi-download"></i> Export</button>
                          </h5>
                          @if($hasReferrals)
                          <div class="mermaid" id="referralMermaid">
                            {!! $mermaid !!}
                          </div>
                          @else
                          <div class="alert alert-info">No referrals found for this patient.</div>
                          @endif
                        </div>
                      </div>
                    </div>
                    <!-- Modal for referral details -->
                    <div class="modal fade" id="referralDetailModal" tabindex="-1" aria-labelledby="referralDetailModalLabel" aria-hidden="true">
                      <div class="modal-dialog">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h5 class="modal-title" id="referralDetailModalLabel">Referral Details</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                          </div>
                          <div class="modal-body" id="referralDetailBody">
                            <!-- Details will be loaded here -->
                          </div>
                        </div>
                      </div>
                    </div>
                </div>
              </div>

            </div>
        </div>
@endsection

@push('scripts')
<script>
// Tooltip
var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
  return new bootstrap.Tooltip(tooltipTriggerEl);
});

// Export diagram as SVG/PNG
function exportMermaidDiagram() {
  const svg = document.querySelector('#referralMermaid svg');
  if (!svg) return alert('Diagram not rendered yet.');
  const serializer = new XMLSerializer();
  const svgString = serializer.serializeToString(svg);
  const blob = new Blob([svgString], {type: 'image/svg+xml'});
  const url = URL.createObjectURL(blob);
  const a = document.createElement('a');
  a.href = url;
  a.download = 'referral-journey.svg';
  document.body.appendChild(a);
  a.click();
  document.body.removeChild(a);
  URL.revokeObjectURL(url);
}
document.getElementById('exportReferralTree').addEventListener('click', exportMermaidDiagram);

// Modal logic for referral details
window.callShowReferralModal = function(referralId) {
  fetch(`/referral/api/details/${referralId}`)
    .then(res => res.json())
    .then(data => {
      let html = `<ul class='list-group'>`;
      html += `<li class='list-group-item'><strong>Date:</strong> ${data.created_at}</li>`;
      html += `<li class='list-group-item'><strong>Status:</strong> ${data.status}</li>`;
      html += `<li class='list-group-item'><strong>Diagnosis:</strong> ${data.diagnosis}</li>`;
      html += `<li class='list-group-item'><strong>Service:</strong> ${data.service}</li>`;
      html += `<li class='list-group-item'><strong>Notes:</strong> ${data.additionalNotes}</li>`;
      html += `</ul>`;
      document.getElementById('referralDetailBody').innerHTML = html;
      var modal = new bootstrap.Modal(document.getElementById('referralDetailModal'));
      modal.show();
    });
}
</script>
@endpush
