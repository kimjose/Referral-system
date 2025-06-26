<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Referral;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index()
    {
        return view('reports.index');
    }

    public function incomingReports(Request $request)
    {
        $user = Auth::user();
        $facility_id = $user->facility_id;
        $filters = $request->only(['start_date', 'end_date', 'status']);
        $statuses = Referral::distinct()->pluck('fhir_status');

        $query = Referral::where('referredFacility', $facility_id);

        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', Carbon::parse($request->start_date)->startOfDay());
        }

        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', Carbon::parse($request->end_date)->endOfDay());
        }

        if ($request->filled('status')) {
            $query->where('fhir_status', $request->status);
        }

        $referrals = $query->with(['patient', 'requesting_facility', 'referred_to_facility', 'service'])->paginate(10);

        return view('reports.incoming-reports', compact('referrals', 'statuses', 'filters'));
    }

    public function outgoingReports(Request $request)
    {
        $user = Auth::user();
        $facility_id = $user->facility_id;
        $filters = $request->only(['start_date', 'end_date', 'status']);
        $statuses = Referral::distinct()->pluck('fhir_status');

        $query = Referral::where('referring_facility_id', $facility_id);

        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', Carbon::parse($request->start_date)->startOfDay());
        }

        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', Carbon::parse($request->end_date)->endOfDay());
        }

        if ($request->filled('status')) {
            $query->where('fhir_status', $request->status);
        }

        $referrals = $query->with(['patient', 'requesting_facility', 'referred_to_facility', 'service'])->paginate(10);

        return view('reports.outgoing-reports', compact('referrals', 'statuses', 'filters'));
    }

    public function completedReports(Request $request)
    {
        $user = Auth::user();
        $facility_id = $user->facility_id;
        $filters = $request->only(['start_date', 'end_date', 'status', 'type']);
        $statuses = Referral::whereIn('fhir_status', ['completed', 'cancelled'])->distinct()->pluck('fhir_status');
        $types = ['Incoming', 'Outgoing'];

        $query = Referral::query();

        if ($request->filled('type')) {
            if ($request->type == 'Incoming') {
                $query->where('referredFacility', $facility_id);
            } elseif ($request->type == 'Outgoing') {
                $query->where('referring_facility_id', $facility_id);
            }
        } else {
            $query->where(function ($q) use ($facility_id) {
                $q->where('referring_facility_id', $facility_id)
                    ->orWhere('referredFacility', $facility_id);
            });
        }

        $query->whereIn('fhir_status', ['completed', 'cancelled']);

        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', Carbon::parse($request->start_date)->startOfDay());
        }

        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', Carbon::parse($request->end_date)->endOfDay());
        }

        if ($request->filled('status')) {
            $query->where('fhir_status', $request->status);
        }

        $referrals = $query->with(['patient', 'requesting_facility', 'referred_to_facility', 'service'])->paginate(10);

        return view('reports.completed-reports', compact('referrals', 'statuses', 'filters', 'types'));
    }
}
