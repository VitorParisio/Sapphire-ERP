<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Category;
use App\Models\ItemVenda;

class Product extends Model
{
    use HasFactory;

    protected $table = 'products';
    
    public $fillable = ['category_id', 'nome', 'preco_compra', 'total_compra', 'preco_venda', 'preco_minimo', 'estoque_minimo', 'validade', 'estoque', 'cod_barra', 'img', 'margem_lucro_per', 'margem_lucro', 'qtd_compra' ,'descricao', 'qtd_atacado', 'preco_atacado', 'cfop', 'ncm', 'cest', 'extipi', 'ceantrib', 'utrib', 'qtrib', 'indtot', 'icms', 'icms_orig', 'icms_csosn', 'pis_cst', 'pis_qbcprod', 'pis_valiqprod', 'cofins_cst', 'cofins_qbcprod', 'cofins_valiqprod', 'vpis', 'vcofins', 'pcredsN', 'vcredicmssn', 'vuntrib', 'ucom'];

    public function categoria(){
        return $this->belongsTo(Category::class,'category_id', 'id');
    }

    public function itemVendas(){
        return $this->hasMany(ItemVenda::class, 'product_id', 'id');
    }


}
