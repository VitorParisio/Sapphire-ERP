<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemNotaTemp extends Model
{
    use HasFactory;

    
    protected $table   = 'item_nota_temps';
    public $timestamps = false;

    protected $fillable = ['product_id', 'item_venda_nfe_id'];

    public function produto(){
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    public function itemVendaNfe(){
        return $this->belongsTo(ItemVendaNfe::class, 'item_venda_nfe_id', 'id');
    }
}
