<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class ItemVenda extends Model
{
    use HasFactory;
    
    protected $table = 'item_vendas';

    public $timestamps = false;
    protected $fillable = ['product_id', 'qtd', 'data_venda' ,'sub_total'];

    public function produto(){
        return $this->belongsTo(Product::class,'product_id', 'id');
    }
    
    public function nfe(){
        return $this->hasMany(Nfe::class,'item_vendas_id', 'id');
    }
}
