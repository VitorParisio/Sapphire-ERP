<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemVendaNfe extends Model
{
    use HasFactory;

    protected $table   = 'item_venda_nves';
    public $timestamps = false;

    protected $fillable = ['product_id','nfe_id', 'qtd', 'data_venda' ,'sub_total'];

    public function produto()
    {
        return $this->hasMany(Product::class, 'product_id', 'id');
    }
    
}
