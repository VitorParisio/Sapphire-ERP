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
    
    public $fillable = ['category_id', 'nome', 'preco_compra', 'preco_venda', 'preco_minimo', 'estoque_minimo', 'validade', 'estoque', 'cod_barra', 'img', 'qtd_compra' ,'descricao', 'cfop', 'ncm', 'extipi', 'ceantrib', 'utrib', 'qtrib', 'indtot', 'icms_orig', 'icms_csosn', 'pis_cst', 'pis_qbcprod', 'pis_valiqprod', 'cofins_cst', 'cofins_qbcprod', 'cofins_valiqprod', 'vpis', 'vcofins', 'pcredsN', 'vcredicmssn', 'vuntrib', 'ucom'];


    public function categoria(){
        return $this->belongsTo(Category::class,'category_id', 'id');
    }

    public function itemVendas(){
        return $this->hasMany(ItemVenda::class, 'product_id', 'id');
    }


}
