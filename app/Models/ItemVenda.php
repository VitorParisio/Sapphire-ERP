<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Product;
use App\Models\Nfe;

class ItemVenda extends Model
{
    use HasFactory;
    
    protected $table = 'item_vendas';

    public $timestamps = false;
    protected $fillable = ['product_id','nfe_id', 'qtd', 'data_venda' ,'sub_total'];

    public function produto()
    {
        return $this->hasMany(Product::class, 'product_id', 'id');
    }
    
    public function nfe()
    {
        return $this->belongsTo(Nfe::class);
    }
}
