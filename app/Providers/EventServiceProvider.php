<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Registered;
use App\Events\TenantEvents\TenantCreated;
use App\Events\TenantEvents\DatabaseCreated;
use App\Listeners\TenantListeners\TenantCreatedDatabase;
use App\Listeners\TenantListeners\RunMigrationsTenant;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],

        TenantCreated::class => [
            TenantCreatedDatabase::class,
        ],

        DatabaseCreated::class => [
            RunMigrationsTenant::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
