<?php

namespace App\Console\Commands;

use App\Models\Tenant;
use App\Tenant\ManagerTenant;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class TenantSeeder extends Command
{ /**
    * The name and signature of the console command.
    *
    * @var string
    */
   protected $signature = 'tenants:seed {id?}';

   /**
    * The console command description.
    *
    * @var string
    */
   protected $description = 'Run Seeder Tenants';
   private $tenant;
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

      if ($this->argument('id'))
      {
           $tenant_by_id = Tenant::find($this->argument('id'));

           if ($tenant_by_id)
               $this->exeCommand($tenant_by_id);

           return;
      }
      
       $tenants = Tenant::all();

       foreach ($tenants as $values)
       {
          $this->exeCommand($values);
       }

       return 0;
   }

   public function exeCommand(Tenant $tenants)
   {
       
       $this->tenant->setConnection($tenants);
         
       Artisan::call('db:seed', [
           '--class' => 'TenantTableSeeder'
       ]);
   }
}
