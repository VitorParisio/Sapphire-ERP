<?php

namespace App\Listeners\TenantListeners;

use App\Events\TenantEvents\DatabaseCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Events\TenantEvents\TenantCreated;
use App\Tenant\Database\DatabaseManager;
use Exception;

class TenantCreatedDatabase
{
    public $database;
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(DatabaseManager $database)
    {
        $this->database = $database;
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(TenantCreated $event)
    {
        $tenant = $event->tenant();
  
        if (!$this->database->createDatabase($tenant))
        {
            throw new \Exception('Erro ao tentar criar a base de dados.');
        }

        event(new DatabaseCreated($tenant));
    }
}
