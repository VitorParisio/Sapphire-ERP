<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContaFechamento extends Model
{
    use HasFactory;

    protected $table   = 'conta_fechamentos';
    public $fillable   = ['venda_cupom_id', 'total_caixa_conta_fechamento'];
    public $timestamps = false;
}
