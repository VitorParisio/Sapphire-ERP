<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Destinatario extends Model
{
    use HasFactory;

    protected $table   = 'destinatarios';
    public $fillable   = ['nome', 'rg_ie', 'cpf_cnpj', 'rua', 'numero', 'bairro', 'complemento', 'cibge', 'cidade', 'uf', 'cep', 'cPais', 'xPais', 'xCpl', 'fone', 'email'];

    public function nfe(){
        return $this->hasMany(Nfe::class, 'destinatario_id', 'id');
    }
}
