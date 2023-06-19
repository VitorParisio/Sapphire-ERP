<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $table    = 'categories';
    protected $fillable = ['categoria', 'descricao'];
    public $timestamps  = false;

    public function products(){
        return $this->hasMany('App\Models\Product', 'category_id');
    }
}
