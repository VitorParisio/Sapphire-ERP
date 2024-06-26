<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContaFechamento extends Model
{
    use HasFactory;

    protected $table   = 'conta_fechamentos';
    public $fillable   = ['caixa_id', 'forma_pagamento_fechamento', 'total_caixa_conta_fechamento', 'total_caixa_informado', 'diferenca_pagamento_fechamento'];
    public $timestamps = false;

}
