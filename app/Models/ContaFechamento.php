<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContaFechamento extends Model
{
    use HasFactory;

    protected $table   = 'conta_fechamentos';
    public $fillable   = ['venda_cupom_id', 'forma_pagamento_fechamento', 'total_caixa_conta_fechamento', 'total_caixa_informado'];
    public $timestamps = false;
}
