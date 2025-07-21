<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\api\services\SmsController;
use App\Http\Controllers\Controller;
use App\Models\m_f_l_s;
use App\Models\Referral;
use App\Models\User;
use App\Notifications\ReferralRequestSent;
use App\Services\AfricasTalking\Sms\SmsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;

class ReferralTabController extends Controller
{

    function test(){
        return "just testing if our api is working";
    }



    function saveTab1Data(Request $request){
        $referral = new Referral;
        $loggedInUserId = $request->input('userId');
        $user = User::where('id', $loggedInUserId)->first();


        $referringOfficer = $request->input('referringOfficer');
        $historyInvestigation = $request->input('historyInvestigation');
        $diagnosis = $request->input('diagnosis');
        $icd11_code = $request->input('icd11_code');
        $reasonReferral = $request->input('reasonReferral');
        $additionalNotes = $request->input('additionalNotes');
        $priority = $request->input('priorityLevel');
        $referringFacilityId =$user->facility_id;

        $referral->referringOfficer = $referringOfficer;
        $referral->historyInvestigation = $historyInvestigation;
        $referral->diagnosis = $diagnosis;
        $referral->icd11_code = $icd11_code;
        $referral->reasonReferral = $reasonReferral;
        $referral->additionalNotes = $additionalNotes;
       $referral->referring_facility_id = $referringFacilityId;
       $referral->clientName = $request->input('clientName');
       $referral->clientUPI = $request->input('clientUPI');
        $referral->priority = $priority;
        $referral->fhir_status = "incomplete";

        $referral->save();
        $referralId = $referral->id;



        return response()->json(
            ['success' => true,
                'referralId' => $referralId]
        );
    }

    function saveTab2Data(Request $request){
        $referralId = $request->referralId;
        // return $referralId;
        $referral = Referral::where('id', $referralId)->firstOrFail();

        $referral->serviceCategory = $request->input('serviceCategory');
        $referral->service = $request->input('service');
        $referral->referredFacility = $request->input('referredFacilityCode');
        $referral->fhir_status = "Pending";

        $referral->save();

        return response()->json(
            ['success' => true,
                'referralId' => $referralId]
        );

    }


    protected SmsService $smsService;
    function saveTab3Data(Request $request){
        $referringFacilityCode = $request->referringFacilityCode;
        $referredFacilityCode = $request->referredFacilityCode;
        $referralId = $request->referralId;
        $referringFacility = m_f_l_s::where('Code', $referringFacilityCode)->first();
        $officialName = $referringFacility->Officialname;
        $this->smsService  = new SmsService();

        // Fetch referral and patient details
        $referral = Referral::find($referralId);
        $patient = $referral ? $referral->patientReffered : null;
        $patientName = $patient ? ($patient->first_name . ' ' . $patient->last_name) : ($referral->clientName ?? '');
        $locator = $patient ? trim("{$patient->village}, {$patient->subCounty}, {$patient->county}", ', ') : '';

        // Compose improved message
        $message = "Patient {$patientName}";
        if ($locator) {
            $message .= " ({$locator})";
        }
        $message .= " has been reviewed by the doctor. Please follow up at the community level.";

        $recipients = "+254725377609, +254735377609";
        $result = $this->smsService->sendSms(2, $recipients, $message);

        $facility = m_f_l_s::where('Code', $referredFacilityCode)->first();
        $notification = new ReferralRequestSent($referralId, $referringFacilityCode, "Referral Request");

        Notification::send($facility, $notification);
//      return Redirect::route('referral.outgoing')->with('success', 'Referral Request Submitted successfully');

        return response()->json($result);

//        return response()->json(
//            ['success' => true]
//        );

    }


    public function apiReferral(Request $request)
    {
        // Extract all the data from the request
        $referralData = $request->all();

        // Validate the data as needed

        // Create a new Referral model and populate it with the data
        $referral = new Referral;
        $referral->fill($referralData);

        // Additional validation and processing can be done here

        // Save the referral data
        $referral->save();

        return response()->json(['success' => true, 'referralId' => $referral->id]);
    }

}
