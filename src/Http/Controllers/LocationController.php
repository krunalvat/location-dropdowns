<?php

namespace Krunalvatvani\LocationDropdowns\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\JsonResponse;
use Krunalvatvani\LocationDropdowns\Models\Country;
use Krunalvatvani\LocationDropdowns\Models\State;
use Krunalvatvani\LocationDropdowns\Models\City;

class LocationController extends Controller
{
    public function getCountries(): JsonResponse
    {
        $countries = Country::orderBy('name')->get(['id', 'name', 'iso2', 'phonecode']);
        
        $countries->transform(function ($country) {
            $country->flag_url = $country->iso2 ? url('/api/locations/flags/' . strtolower($country->iso2)) : null;
            return $country;
        });

        return response()->json($countries);
    }

    public function getStates($countryId): JsonResponse
    {
        $states = State::where('country_id', $countryId)->orderBy('name')->get(['id', 'name', 'state_code']);
        return response()->json($states);
    }

    public function getCities($stateId): JsonResponse
    {
        $cities = City::where('state_id', $stateId)->orderBy('name')->get(['id', 'name']);
        return response()->json($cities);
    }

    public function getFlag($iso2)
    {
        $url = 'https://flagcdn.com/w20/' . strtolower($iso2) . '.png';
        
        $response = \Illuminate\Support\Facades\Http::withoutVerifying()->get($url);
        
        if ($response->successful()) {
            return response($response->body())->header('Content-Type', 'image/png');
        }
        
        return response()->json(['error' => 'Flag not found'], 404);
    }
}
