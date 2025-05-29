<?php

namespace App\Http\Controllers;

use App\Models\Gad7;
use App\Models\Ptsd5;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExtraFormsController extends Controller
{
    //

    public function addGad7()
    {
        $today = date('Y-m-d');
        $patients = Patient::all();
        $assessmentScores = [];
        $notAtAll['name'] = 'Not at all';
        $notAtAll['value'] = 0;
        $severalDays['name'] = 'Several days';
        $severalDays['value'] = 1;
        $moreThanOneDay['name'] = 'More than half the days';
        $moreThanOneDay['value'] = 2;
        $everyDay['name'] = 'Every day';
        $everyDay['value'] = 3;
        array_push($assessmentScores, $notAtAll);
        array_push($assessmentScores, $severalDays);
        array_push($assessmentScores, $moreThanOneDay);
        array_push($assessmentScores, $everyDay);
        return view('extra_forms.gad7', compact( 'patients', 'assessmentScores', 'today'));
    }

    public function storeGad7(Request $request)
    {
        $validated = $request->validate([
            'patient_id' => ['required'],
            'assessment_date' => ['required'],
            'anxious_nervous' => ['required'],
            'uncontrollable_worrying' => ['required'],
            'worrying_too_much' => ['required'],
            'trouble_relaxing' => ['required'],
            'restless' => ['required'],
            'irritable' => ['required'],
            'afraid' => ['required']
        ]);
        $validated["user_id"] = Auth::id();
        Gad7::create($validated);
        return redirect()->back()->with('success', 'GAD7 Form Submitted successfully');
    }

    public function addPtsd5(){
        $today = date('Y-m-d');
        $patients = Patient::all();
        return view('extra_forms.ptsd5', compact('patients', 'today'));
    }

    public function storePtsd5(Request $request){
        $validated = $request->validate([
            'patient_id' => ['required'],
            'assessment_date' => ['required'],
            'experienced_trauma' => ['required'],
        ]);
        $validated["user_id"] = Auth::id();
        Ptsd5::create($validated);
        return redirect()->back()->with('success', 'PTSD5 Form Submitted successfully');
    }

}
