<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PDV extends Model
{
    use HasFactory;

    protected $table = 'p_d_v_s';
    public $timestamps = false;

    protected $fillable = ['product_id', 'item_venda_id'];
}
