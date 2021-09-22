<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Categorias extends Model
{
    protected $fillable = [
        'name',
        'description',
        'valor',
        'forma',
        'inicial',
        'final',
        'acima',
        'status'
    ];
    protected $table = 'categorias';

    public function vendas(){
        return $this->hasMany(Vendas::class , 'categoria_id');
    }
    public function consumers(){
        return $this->hasMany(Consumers::class, 'categoria_id');
    }
    public function crt(){
        return $this->hasMany(Crt::class, 'categoria_id');
    }
}