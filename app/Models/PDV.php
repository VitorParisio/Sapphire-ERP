<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Product;
use App\Models\itemVenda;

class PDV extends Model
{
    use HasFactory;

    protected $table = 'p_d_v_s';
    public $timestamps = false;

    protected $fillable = ['product_id', 'item_venda_id'];

    public function produto(){
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    public function itemVenda(){
        return $this->belongsTo(ItemVenda::class, 'item_venda_id', 'id');
    }
}
