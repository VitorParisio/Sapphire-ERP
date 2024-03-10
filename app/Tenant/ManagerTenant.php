<?php

namespace App\Tenant;

use App\Models\Tenant;
use Illuminate\Support\Facades\DB;

class ManagerTenant {

    public function setConnection(Tenant $tenant)
    {
        DB::purge('tenant');
     
        config()->set('database.connections.tenant.database', $tenant->db_database);
        config()->set('database.connections.tenant.host', $tenant->db_hostname);
        config()->set('database.connections.tenant.username', $tenant->db_username);
        config()->set('database.connections.tenant.password', $tenant->db_password);
        
        DB::connection('tenant');
    }

    public function domainIsMain()
    {
        // dd(request()->getHost());
        return request()->getHost() == config('tenant.domain_main');
    }
}