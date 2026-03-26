<?php

namespace App\Http\Controllers\Api\Driver;

use App\Http\Controllers\Controller;
use App\Models\District;
use App\Models\DriverRule;
use App\Models\InsuranceCompany;
use App\Models\Service;
use App\Models\VehicleType;
use Illuminate\Http\JsonResponse;

class ServiceController extends Controller
{
    /**
     * Get all enabled services.
     */
    public function services(): JsonResponse
    {
        $services = Service::where('enable', true)->get();

        return response()->json([
            'success' => true,
            'message' => 'Services retrieved successfully.',
            'data' => $services,
        ]);
    }

    /**
     * Get all enabled vehicle types.
     */
    public function vehicleTypes(): JsonResponse
    {
        $vehicleTypes = VehicleType::where('enable', true)->get();

        return response()->json([
            'success' => true,
            'message' => 'Vehicle types retrieved successfully.',
            'data' => $vehicleTypes,
        ]);
    }

    /**
     * Get all published districts.
     */
    public function districts(): JsonResponse
    {
        $districts = District::where('publish', true)->get();

        return response()->json([
            'success' => true,
            'message' => 'Districts retrieved successfully.',
            'data' => $districts,
        ]);
    }

    /**
     * Get all insurance companies.
     */
    public function insuranceCompanies(): JsonResponse
    {
        $companies = InsuranceCompany::all();

        return response()->json([
            'success' => true,
            'message' => 'Insurance companies retrieved successfully.',
            'data' => $companies,
        ]);
    }

    /**
     * Get all enabled driver rules.
     */
    public function driverRules(): JsonResponse
    {
        $rules = DriverRule::where('enable', true)->get();

        return response()->json([
            'success' => true,
            'message' => 'Driver rules retrieved successfully.',
            'data' => $rules,
        ]);
    }
}
