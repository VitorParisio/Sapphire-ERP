<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VendaCupom extends Model
{
    use HasFactory;

    protected $table = 'venda_cupoms';

    protected $fillable = ['cupom_id', 'total_venda', 'forma_pagamento', 'valor_recebido', 'desconto', 'troco'];
}
