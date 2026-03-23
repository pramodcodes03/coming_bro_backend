<?php

namespace App\Http\Controllers\Api\Driver;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\FuelType;
use App\Models\State;
use App\Models\VehicleCompany;
use App\Models\VehicleModelEntry;
use App\Models\Zone;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    /**
     * Get all states.
     */
    public function states(): JsonResponse
    {
        $states = State::all();

        return response()->json([
            'success' => true,
            'message' => 'States retrieved successfully.',
            'data' => $states,
        ]);
    }

    /**
     * Get cities (optionally by state_id).
     */
    public function cities(Request $request): JsonResponse
    {
        $query = City::query();

        if ($request->has('state_id') && $request->state_id) {
            $query->where('state_id', $request->state_id);
        }

        $cities = $query->get();

        return response()->json([
            'success' => true,
            'message' => 'Cities retrieved successfully.',
            'data' => $cities,
        ]);
    }

    /**
     * Get vehicle companies.
     */
    public function vehicleCompanies(): JsonResponse
    {
        $companies = VehicleCompany::all();

        return response()->json([
            'success' => true,
            'message' => 'Vehicle companies retrieved successfully.',
            'data' => $companies,
        ]);
    }

    /**
     * Get vehicle models (optionally by company_id).
     */
    public function vehicleModels(Request $request): JsonResponse
    {
        $query = VehicleModelEntry::query();

        if ($request->has('company_id') && $request->company_id) {
            $query->where('company_id', $request->company_id);
        }

        $models = $query->get();

        return response()->json([
            'success' => true,
            'message' => 'Vehicle models retrieved successfully.',
            'data' => $models,
        ]);
    }

    /**
     * Get fuel types.
     */
    public function fuelTypes(): JsonResponse
    {
        $fuelTypes = FuelType::all();

        return response()->json([
            'success' => true,
            'message' => 'Fuel types retrieved successfully.',
            'data' => $fuelTypes,
        ]);
    }

    /**
     * Get zones.
     */
    public function zones(): JsonResponse
    {
        $zones = Zone::where('publish', true)->get();

        return response()->json([
            'success' => true,
            'message' => 'Zones retrieved successfully.',
            'data' => $zones,
        ]);
    }
}
