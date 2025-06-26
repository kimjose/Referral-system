<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Patient;
use App\Models\Referral;

class VerificationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the patient verification page.
     */
    public function showPatientVerification(Request $request)
    {
        $patient = null;
        if ($request->filled('patient_id')) {
            $patient = Patient::where('idNo', $request->patient_id)->first();
        }
        return view('verification.verify-patient', compact('patient'));
    }

    /**
     * Show the referral verification page.
     */
    public function showReferralVerification(Request $request)
    {
        $referral = null;
        if ($request->filled('referral_id')) {
            $referral = Referral::find($request->referral_id);
        }
        return view('verification.verify-referral', compact('referral'));
    }
} 