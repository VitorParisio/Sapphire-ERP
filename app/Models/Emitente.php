<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Produto;

class Emitente extends Model
{
    use HasFactory;

    protected $table = 'emitentes';
    
    public $fillable   = ['cnpj', 'ie', 'im', 'cnae', 'razao_social', 'nome_fantasia', 'cep', 'rua', 'numero', 'complemento', 'bairro', 'cidade', 'uf', 'cuf', 'cibge', 'telefone', 'email', 'certificado_a1', 'senha_certificado', 'host_email', 'senha_email', 'tokenIBPT', 'csc', 'csc_id', 'crt'];
    public $timestamps = false;

    
    public function produto(){
        return $this->hasMany(Produto::class, 'product_id', 'id');
    }
}
