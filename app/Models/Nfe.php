<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nfe extends Model
{
    use HasFactory;

    protected $table = 'nves';

    public $fillable = ['emitente_id', 'destinatario_id', 'item_venda_id', 'status_id', 'nro_nfe', 'serie_nfe', 'finNFe', 'path_xml', 'path_file', 'nProt', 'chave_nfe', 'dhRecbto', 'dhRegEvento', 'xMotivo', 'digVal', 'cStat', 'xEvento', 'ambiente', 'dataRecibo', 'horaRecibo', 'modFrete', 'vTroco'];

    // public function emitente(){
    //     return $this->hasMany('App\Models\ItemVenda', 'product_id');
    // }

    // public function itemVendas(){
    //     return $this->hasMany('App\Models\ItemVenda', 'product_id');
    // }

}
