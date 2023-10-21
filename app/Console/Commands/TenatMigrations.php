<?php

namespace App\Console\Commands;

use App\Models\Tenant;
use App\Tenant\ManagerTenant;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class TenatMigrations extends Command
{

    private $tenant;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tenants:migrations {id?} {--refresh}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(ManagerTenant $tenant)
    {
        parent::__construct();

        $this->tenant = $tenant;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        if ($id = $this->argument('id'))
        {
            $tenant = Tenant::find($id);
            
            if ($tenant)
               $this->exeCommand($tenant); 

            return; 
        }

        $tenants = Tenant::all();

        foreach($tenants as $tenant)
        {
            $this->exeCommand($tenant);
        }
        return 0;
    }

    public function exeCommand(Tenant $tenant)
    {
        $command =  $this->option('refresh') ? 'migrate:refresh' : 'migrate';

        $this->tenant->setConnection($tenant);

        Artisan::call($command, [
            '--force' => true,
            '--path'  => '/database/migrations/tenants'
        ]);
    }
}
