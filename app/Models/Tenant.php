<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tenant extends Model
{
    use HasFactory;

    protected $table    = 'tenants';
    protected $fillable = ['nome', 'dominio', 'db_database', 'db_hostname', 'db_username', 'db_password','status'];
}
