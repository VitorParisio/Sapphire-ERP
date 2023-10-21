<?php

namespace App\Tenant\Database;

use Illuminate\Support\Facades\DB;
use App\Models\Tenant;

class DatabaseManager{
    
    public function createDatabase (Tenant $tenant)
    {
       return DB::statement("
            CREATE DATABASE {$tenant->db_database} CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci
        ");
    } 
}