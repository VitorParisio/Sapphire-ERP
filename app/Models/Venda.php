<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Venda extends Model
{
    use HasFactory;

    protected $table = 'vendas';

    protected $fillable = ['nfe_id', 'total_venda', 'forma_pagamento', 'valor_recebido', 'desconto', 'troco'];

    
}
