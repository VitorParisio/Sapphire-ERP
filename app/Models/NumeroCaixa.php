<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NumeroCaixa extends Model
{
    use HasFactory;

    protected $table   = 'numero_caixas';
    public $timestamps = false;
    
    protected $fillable = ['user_id', 'numero', 'descricao'];
}
