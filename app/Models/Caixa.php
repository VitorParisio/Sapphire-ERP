<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Caixa extends Model
{
    use HasFactory;
    
    protected $table = 'caixas';
    public $timestamps = false;

    protected $fillable = ['nro_caixa_id', 'user_abertura_id', 'user_fechamento_id', 'data_abertura', 'data_fechamento', 'horario_abertura', 'horario_fechamento','valor_abertura', 'valor_fechamento', 'valor_vendido', 'sangria', 'suplemento', 'total_caixa', 'status'];
}
