<?php

namespace App\Http\Controllers;
use App\Models\m_f_l_s;
use App\Models\Service;
use App\Models\ServiceCategory;
use App\Models\User;
use App\Notifications\ReferralRequestSent;
use App\utils\SendReferral;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Redirect;
use App\Models\Patient;
use App\Models\Referral;
use App\Models\Mappings;
use App\Services\FhirService;

class ReferralController extends Controller
{
    protected $fhirService;

    public function __construct(FhirService $fhirService)
    {
        $this->fhirService = $fhirService;
    }

    public function show($tab)
    {
        $activeTab = $tab; // Store the active tab to determine which tab should be marked as active

        $diagnosis = Mappings::select('id', 'from concept name')->get();
//        $patientId = request()->query('patientId');
//        $patientId2 = request()->input('patientId');
//
//        dd($patientId, $patientId2);

//        dd(request()->all());
        if ($tab === 'tab1') {
            $patientId = request()->input('patientId');
//            return "the patient id is:".$patientId;

            if($patientId == null){
                return redirect()->route('referrals.worklist')->with('error', 'No patient selected');
            }

            $patientDetails = Patient::where('id', $patientId)->first();

            return view('referrals.referralProcess.tabs.tab1',
                compact('activeTab'))->with(['patient' => $patientDetails,
                'diagnosis' => $diagnosis]);

        } elseif ($tab === 'tab2') {
            $serviceCategories = ServiceCategory::all();
            $services = Service::all();
            $facilities = m_f_l_s::all();

            $referralId = request()->input('referralId');
            if($referralId == null){
                return redirect()->route('referrals.worklist')->with('error', 'No patient selected');
            }

            $referral = Referral::where('id', $referralId)->first();
            if ($referral == null){
                $patientDetails = [];

            }else {

                $patientUpi = $referral->clientUPI;
                $patientDetails = Patient::where('upi', $patientUpi)->first();
            }

            return view('referrals.referralProcess.tabs.tab2',
                compact('activeTab'))->with(['patient' => $patientDetails,
            'serviceCategories' => $serviceCategories, 'services' => $services,
                'referralId' => $referralId, 'facilities' => $facilities]);

        } elseif ($tab === 'tab3') {
            $referralId =request()->input('referralId');

            if($referralId == null){
                return redirect()->route('referrals.worklist')->with('error', 'No patient selected');
            }
            $referral = Referral::where('id', $referralId)->first();
            if ($referral == null){
                $patientDetails = [];
                return redirect()->route('referrals.worklist')->with('error', 'No patient selected');
            }else {

                $patientUpi = $referral->clientUPI;
                $patientDetails = Patient::where('upi', $patientUpi)->first();
                $referralId = $referral->id;

                $user = Auth::user();
                $userFacility = $user->userFacility;

                $facility = m_f_l_s::where('Code', $referral->referredFacility)->first();
//                $notification = new ReferralRequestSent($referralId, $userFacility->Code, "Referral Request");
//
//                Notification::send($facility, $notification);
//                return Redirect::route('referral.outgoing')->with('success', 'Referral Request Submitted successfully');
                return view('referrals.referralProcess.tabs.tab3',
                    compact('activeTab'))->with(['patient' => $patientDetails, 'referral' => $referral]);

            }
//            return view('referrals.referralProcess.tabs.tab3',
//                compact('activeTab'))->with(['patient' => $patientDetails, 'referral' => $referral]);

        } elseif ($tab === 'tab4') {
            return redirect()->route('referrals.worklist')->with('success', 'message sent successfully');
        }
        return redirect()->route('referrals.worklist')->with('error', 'No tab selected');
    }




    public function saveTabData($tab, Request $request){
        $activeTab = $tab; // Store the active tab to determine which tab should be marked as active

        $patientDetails = Patient::where('id', 1)->first();
        $diagnosis = Mappings::select('id', 'from concept name')->get();
        $serviceCategories = ServiceCategory::all();

        if ($tab === 'tab1'){

            return view('referrals.referralProcess.tabs.tab2',
                compact('activeTab'))->with(['patient' => $patientDetails,
                'diagnosis' => $diagnosis,
                'serviceCategories' => $serviceCategories]);

        }elseif ($tab === 'tab2'){

            return view('referrals.referralProcess.tabs.tab3',
                compact('activeTab'))->with(['patient' => $patientDetails]);


        }elseif($tab === 'tab3'){



        }else{
            return "our engineers are working on the issue";
        }
    }



    public function outgoingReferralTabs(){
        return view('referrals/referralProcess/tabs/outgoingReferralTabs');
    }


    public function index(){
        $referrals = [];
        return view('referrals.index')->with(['referrals' => $referrals]);
    }


    public function addReferral(){
        return view('referrals.addReferral');
    }

    public function destroy(referral $referral) {
        $referralRequests = Referral::where('id', $referral->id)->first();

        // Perform any additional checks or authorization if needed
        if (!$referralRequests) {
            return redirect()->route('referrals.outgoing')->with('error', 'Record not found');
        }
        // Delete the record
        $referralRequests->delete();
        return redirect()->route('referrals.outgoing.outgoing')->with('success', 'Record deleted successfully');
    }

    public function facilities(){

        return view('referrals.facilities');
    }

    public function medicalTerms(){
        return view('referrals.medicalTerms');
    }

    public function worklist(){
        $patients = Patient::all(); // Retrieve all patients from the database
        return view('referrals.worklist', ['patients' => $patients]);
    }


    public function createreferal(Patient $patient){
        $facilities = m_f_l_s::all();
        $patientDetails = Patient::where('id', $patient->id)->first();
        $diagnosis = Mappings::select('id', 'from concept name')->get();
        return view('referrals.createReferral')->with(['facilities' => $facilities, 'patient' => $patientDetails, 'diagnosis' => $diagnosis]);
    }

    public function viewReferal(Referral $referral){
        // $facilities = m_f_l_s::take(20)->get();
        // $patientDetails = Patient::where('id', $patient->id)->first();
        $referralRequests = Referral::where('id', $referral->id)->first();
        $patientDetails = Patient::where('upi', $referral->clientUPI)->first();

        $data = [
            'referral' => $referralRequests,
            'patient' => $patientDetails,
        ];


        return view('referrals.outgoing.viewReferral', $data);
    }

    public function viewIncomingReferal(Referral $referral){

        // $facilities = m_f_l_s::take(20)->get();
        // $patientDetails = Patient::where('id', $patient->id)->first();
        $referralRequests = Referral::where('id', $referral->id)->first();
        $patientDetails = Patient::where('upi', $referral->clientUPI)->first();

        $data = [
            'referral' => $referralRequests,
            'patient' => $patientDetails,
        ];


        return view('referrals.incoming.viewReferral', $data);
    }


    public function submitReferral(Request $request)
    {
        // First validate the FHIR ServiceRequest
        $validator = $this->fhirService->validateServiceRequest($request->all());
        if ($validator->fails()) {
            return response()->json(
                $this->fhirService->createOperationOutcome('error', 'invalid', $validator->errors()->first()),
                422
            );
        }

        try {
            $referral = new Referral();
            $referral->fill($request->all());
            $referral->save();

            return response()->json([
                'resourceType' => 'Bundle',
                'type' => 'transaction-response',
                'entry' => [
                    [
                        'response' => [
                            'status' => '201 Created',
                            'location' => "ServiceRequest/{$referral->id}"
                        ]
                    ]
                ]
            ], 201);
        } catch (\Exception $e) {
            return response()->json(
                $this->fhirService->createOperationOutcome('error', 'processing', 'Error creating referral: ' . $e->getMessage()),
                500
            );
        }
    }



    public function outgoing(){
        $user = Auth::user();

        // Check if the user has a facility assigned.
        if (!$user->userFacility) {
            // If not, redirect to the dashboard with an error message.
            return redirect()->route('admin.dashboard')->with('error', 'You are not assigned to a facility and cannot view outgoing referrals.');
        }

        $loggedInuserFacilityCode = $user->userFacility->Code;
        $referrals = Referral::where('referring_facility_id', $loggedInuserFacilityCode)
            ->orderBy('created_at', 'desc')
            ->get();
        return view('referrals.outgoing.outgoing',['referralRequests'=>$referrals]);
    }



    public function incomingReferrals(){
        $user = Auth::user();

        // Check if the user has a facility assigned.
        if (!$user->userFacility) {
            // If not, redirect to the dashboard with an error message.
            return redirect()->route('admin.dashboard')->with('error', 'You are not assigned to a facility and cannot view incoming referrals.');
        }

        $loggedInuserFacilityCode = $user->userFacility->Code;

        $referrals = Referral::where('referredFacility', $loggedInuserFacilityCode)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('referrals.index')->with(['referralRequests' => $referrals]);
    }



    public function acceptReferralRequest(Referral $referral){
        $referral->status = "Accepted"; // Assign the new value to the column
        $referral->save();

        $user = Auth::user();
        $userFacility = $user->userFacility;
        $userFacility->unreadNotifications
            ->where('data.referral_id', $referral->id)
            ->each(function ($notification) {
                $notification->markAsRead();
                // Perform any other necessary updates
            });



        $referralRequests = Referral::orderBy('created_at', 'desc')->get();
        //$referralRequests = referralRequest::all();
        return  redirect()->route('referrals.incoming')->with(['referralRequests' => $referralRequests]);

    }

    public function rejectReferralRequest(Referral $referral){

        $referral->status = "Rejected"; // Assign the new value to the column
        $referral->save();

        //changing the status of the notification
        $user = Auth::user();
        $userFacility = $user->userFacility;
        $userFacility->unreadNotifications
            ->where('data.referral_id', $referral->id)
            ->each(function ($notification) {
                $notification->markAsRead();
                //you can add aditional code here for more functionality

            });

        //sending notification to referral facility of rejected referral
        $referralId = $referral->id;
        $facility = m_f_l_s::where('Code', $referral->referring_facility_id)->first();
        $notification = new ReferralRequestSent($referralId,$userFacility->Code , "Referral Request Rejected");

        Notification::send($facility, $notification);

        $referralRequests = Referral::orderBy('created_at', 'desc')->get();

        return redirect()->route('referrals.incoming')->with(['referralRequests' => $referralRequests]);
    }




    public function reviewed(){

        return view('referrals.incoming.reviewed');
    }

    public function counterReferral(){

        return view('referrals.incoming.counter-referral');
    }






















    public function fhirJson(Request $request)
    {
        // Check if legacy format is requested
        if ($request->has('legacy') && $request->legacy === 'true') {
            return response()->json([
                'resourceType' => 'referralRequest',
                "id" => "df792cca-af36-47ab-81b3-c9770fbe4bfd",
                "status" => "active",
                "subject"=> [
                    "reference" => "https://client.registry/0-TGGA-rrTT",
                    "code" => '0-TGGA-rrTT',
                    "display"=> "John Doe"
                ],
                "priority"=> "STAT",
                "requester"=> [
                    "reference"=> "https://worker.registry/0-TGGA-TYRTT",
                    "code" => "0-TGGA-TYRTT",
                    "display"=> "Dr. Jane Smith"
                ],
                "specialty"=> [
                    "coding"=> [
                        "system"=> "http://nhdd.health.go.ke",
                        "code"=> "394585001",
                        "display"=> "Cardiology"
                    ],
                    "text" => "Cardiology"
                ],
                "recipient"=> [
                    [
                        "reference"=> "https://facility-registy.com/t6gr86gfrr",
                        "code" => "t6gr86gfrr",
                        "display" => "Coast General",
                    ]
                ],
                "reasonCode"=> [
                    "coding"=> [
                        "system"=> "http://nhdd.health.go.ke/162864005",
                        "code"=> "162864005",
                        "display"=> "Chest pain"
                    ],
                    "text" => "Chest Pain",
                ],
                "authoredOn"=> "2023-04-13T12:00:00Z",
                "reasonReference" => [
                    [
                        "reference" => 'https://shr.go.ke/345678',
                        "code" => '345678',
                        "display" => 'specialized treatment'
                    ],
                ],
                "relevantHistory" => [
                    [
                        "reference" => 'https://shr.go.ke/43GS556GSG',
                        "code" => '43GS556GSG',
                        'display' => 'Malaria include vitals',
                    ],
                    [
                        "reference" => 'https://shr.go.ke/43GS556G8G',
                        "code" => '43GS556G8G',
                        "display" => 'TB Infection',
                    ]
                ],
                "type" => [
                    "coding" => [
                        "reference" => 'https://nhdd.go.ke/46765efg567',
                        "code" => '46765efg567',
                        "display" => 'Referral to cardiology service',
                    ],
                ],
                "context" => [
                    "reference" => "https://shr.go.ke/encounter/344S656S55S",
                    "code" => "344S656S55S",
                    "text" => "Episode 10",
                ],
                "supportingInfo" => [
                    [
                        "reference" => "https://shr.go.ke/34567823H",
                        "code" => "34567823H",
                        "text" => "Medical history, lab results and clinical notes and triage",
                    ],
                    [
                        "reference" => "https://shr.go.ke/34567823H",
                        "code" => "34567823H",
                        "text" => "Medical history, lab results and clinical notes",
                    ],
                    [
                        "reference" => "https://shr.go.ke/34567823H",
                        "code" => "34567823H",
                        "text" => "Medical history, lab results and clinical notes",
                    ]
                ]
            ]);
        }

        // Default to new FHIR-compliant format
        $referral = Referral::latest()->first();
        
        if (!$referral) {
            return response()->json(
                $this->fhirService->createOperationOutcome('error', 'not-found', 'No referral found'),
                404
            );
        }

        return response()->json($this->fhirService->referralToServiceRequest($referral));
    }

    public function validateReferral(Request $request)
    {
        $validator = $this->fhirService->validateServiceRequest($request->all());

        if ($validator->fails()) {
            return response()->json(
                $this->fhirService->createOperationOutcome('error', 'invalid', $validator->errors()->first()),
                422
            );
        }

        return response()->json(
            $this->fhirService->createOperationOutcome('information', 'informational', 'Referral is valid')
        );
    }
}
