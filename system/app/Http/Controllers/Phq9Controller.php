<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\Phq9Assessment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Phq9Controller extends Controller
{
    //

    public function addAssessment(){
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
        return view('phq9', compact('patients', 'assessmentScores', 'today'));
    }

    public function storeAssessment(Request $request){
        $validated = $request->validate([
            "patient_id" => ["required"],
            "assessment_date" => ["required"],
            "little_interest" => ["required"],
            "feeling_down" => ["required"],
            "trouble_sleeping" => ["required"],
            "feeling_tired" => ["required"],
            "poor_appetite" => ["required"],
            "feeling_bad_about_self" => ["required"],
            "trouble_concentrating" => ["required"],
            "slow_or_fidgety" => ["required"],
            "thoughts_of_self_hurt" => ["required"],
            "difficult_caused" => ["required"]
        ]);
        $validated["user_id"] = Auth::id();
        Phq9Assessment::create($validated);
        //return redirect()->back()->with('success', 'PHQ9 Form Submitted successfully');
    }
}
