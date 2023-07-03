<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Emitente;
use App\Models\Destinatario;
use App\Models\ItemVenda;

class Nfe extends Model
{
    use HasFactory;

    protected $table = 'nves';

    public $fillable = ['emitente_id', 'destinatario_id', 'status_id', 'nro_nfe', 'serie_nfe', 'finNFe', 'path_xml', 'path_file', 'nProt', 'chave_nfe', 'dhRecbto', 'dhRegEvento', 'xMotivo', 'xJust' , 'digVal', 'cStat', 'xEvento', 'nSeqEvento' ,'ambiente', 'dataRecibo', 'horaRecibo', 'modFrete', 'vTroco', 'tPag', 'vPag'];

    public function emitente(){
        return $this->belongsTo(Emitente::class);
    }

    public function destinatario(){
        return $this->belongsTo(Destinatario::class);
    }

    public function itemVenda()
    {
        return $this->hasMany(ItemVenda::class,'nfe_id', 'id');
    }

}
