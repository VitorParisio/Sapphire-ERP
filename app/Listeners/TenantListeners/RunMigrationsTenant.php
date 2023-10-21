<?php

namespace App\Listeners\TenantListeners;

use App\Events\TenantEvents\DatabaseCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Artisan;

class RunMigrationsTenant
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(DatabaseCreated $event)
    {
        $tenant = $event->tenant();

        $migrations = Artisan::call('tenants:migrations', [
            'id' => $tenant->id
        ]);

        if ($migrations === 0)
        {
            Artisan::call('db:seed', [
                '--class' => 'TenantTableSeeder'
            ]);
        }

        $migrations === 0;
    }
}
