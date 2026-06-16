<?php

namespace Krunalvatvani\LocationDropdowns\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;

class LocationSyncService
{
    protected function getBaseUrl(): string
    {
        // The data source URL is obfuscated to prevent external visibility
        $encodedSource = 'aHR0cHM6Ly9yYXcuZ2l0aHVidXNlcmNvbnRlbnQuY29tL2hpaWFtcm9oaXQvQ291bnRyaWVzLVN0YXRlcy1DaXRpZXMtZGF0YWJhc2UvbWFzdGVyLw==';
        return rtrim(base64_decode($encodedSource), '/') . '/';
    }

    public function syncCountries(): void
    {
        $response = Http::withoutVerifying()->get($this->getBaseUrl() . 'countries.json');
        if ($response->successful()) {
            $countries = $response->json()['countries'] ?? [];
            $data = [];
            foreach ($countries as $country) {
                $data[] = [
                    'id' => $country['id'],
                    'name' => $country['name'],
                    'iso2' => $country['sortname'] ?? null,
                    'iso3' => null,
                    'phonecode' => $country['phoneCode'] ?? null,
                    'capital' => null,
                    'currency' => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            
            foreach (array_chunk($data, 500) as $chunk) {
                DB::table('countries')->upsert($chunk, ['id'], ['name', 'iso2', 'iso3', 'phonecode', 'capital', 'currency', 'updated_at']);
            }
        }
    }

    public function syncStates(): void
    {
        $response = Http::withoutVerifying()->get($this->getBaseUrl() . 'states.json');
        if ($response->successful()) {
            $states = $response->json()['states'] ?? [];
            $data = [];
            foreach ($states as $state) {
                $data[] = [
                    'id' => $state['id'],
                    'country_id' => $state['country_id'],
                    'name' => $state['name'],
                    'state_code' => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            foreach (array_chunk($data, 500) as $chunk) {
                DB::table('states')->upsert($chunk, ['id'], ['country_id', 'name', 'state_code', 'updated_at']);
            }
        }
    }

    public function syncCities(): void
    {
        ini_set('memory_limit', '-1');
        $response = Http::withoutVerifying()->get($this->getBaseUrl() . 'cities.json');
        if ($response->successful()) {
            $cities = $response->json()['cities'] ?? [];
            $data = [];
            
            // Map state_id to country_id so we don't have to alter the database schema
            $stateToCountry = DB::table('states')->pluck('country_id', 'id')->toArray();

            foreach ($cities as $city) {
                // If a state doesn't exist, we skip it to prevent foreign key errors
                if (!isset($stateToCountry[$city['state_id']])) {
                    continue;
                }

                $data[] = [
                    'id' => $city['id'],
                    'state_id' => $city['state_id'],
                    'country_id' => $stateToCountry[$city['state_id']],
                    'name' => $city['name'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            foreach (array_chunk($data, 1000) as $chunk) {
                DB::table('cities')->upsert($chunk, ['id'], ['state_id', 'country_id', 'name', 'updated_at']);
            }
        }
    }
}
