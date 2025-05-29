<?php

namespace App\Http\Controllers;

use App\Services\MFLService;
use Illuminate\Http\Request;

class MFLController extends Controller
{
    protected $mflService;

    public function __construct(MFLService $mflService)
    {
        $this->mflService = $mflService;
    }

    public function sync(Request $request)
    {
        $filters = $request->only(['name', 'code', 'facility_type', 'operation_status', 'ward']);

        $result = $this->mflService->sync($filters);

        if (!$result['success']) {
            return response()->json($result, 500);
        }

        return response()->json($result);
    }
} 