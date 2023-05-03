<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Nfe;

class Statu extends Model
{
    use HasFactory;
    
    protected $table = 'status';
    public $fillable = ['situacao', 'cor'];
    public $timestamps = false;

    public function notaFiscal(){
        return $this->hasOne(Nfe::class, 'status_id');
    }
}
