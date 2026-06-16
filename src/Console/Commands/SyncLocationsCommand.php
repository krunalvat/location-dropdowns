<?php

namespace Krunalvatvani\LocationDropdowns\Console\Commands;

use Illuminate\Console\Command;
use Krunalvatvani\LocationDropdowns\Services\LocationSyncService;

class SyncLocationsCommand extends Command
{
    protected $signature = 'locations:sync';
    protected $description = 'Synchronize country, state, and city data from third-party repository';

    public function handle(LocationSyncService $syncService)
    {
        $this->info('Starting location synchronization...');

        $this->info('Syncing countries...');
        $syncService->syncCountries();
        $this->info('Countries synced successfully.');

        $this->info('Syncing states...');
        $syncService->syncStates();
        $this->info('States synced successfully.');

        $this->info('Syncing cities (this may take a while)...');
        $syncService->syncCities();
        $this->info('Cities synced successfully.');

        $this->info('All location data has been synchronized!');
    }
}
