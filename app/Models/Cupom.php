<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cupom extends Model
{
    use HasFactory;

    protected $table   = 'cupoms';
    public $timestamps = false;

    protected $fillable = ['user_id', 'caixa_id', 'nro_cupom'];
}
