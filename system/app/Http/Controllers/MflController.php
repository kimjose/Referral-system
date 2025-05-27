<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Services\MFLService;
use Illuminate\Http\Request;

class MflController extends Controller
{
    protected $mflService;

    public function __construct(MFLService $mflService)
    {
        $this->mflService = $mflService;
    }

    public function getFacilityFromService(Request $request)
    {
        try {
            $serviceId = $request->service_id;
            $ownerName = $request->owner_name;
            
            $facilities = $this->mflService->getFacilitiesByService(
                $serviceId,
                $ownerName === 'Ministry of Health' ? '6a833136-5f50-46d9-b1f9-5f961a42249f' : 'd9a0ce65-baeb-4f3b-81e3-083a24403e92'
            );

            return response()->json($facilities);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getServiceFromCategory(Request $request)
    {
        try {
            $categoryName = $request->input('category_name');
            $services = Service::where('category_name', $categoryName)->get();
            return response()->json($services);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getServiceCategories()
    {
        try {
            $categories = $this->mflService->getServiceCategories();
            return response()->json($categories);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getFacilityTypes()
    {
        try {
            $types = $this->mflService->getFacilityTypes();
            return response()->json($types);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function tokenGenerator(){
         $token =  KmhflTokenGenerator::tokenGenerator();
         return $token;
    }
}
