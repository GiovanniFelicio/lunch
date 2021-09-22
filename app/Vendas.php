<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Vendas extends Model
{
    protected $fillable = [
        'tipo',
        'consumer_id',
        'consumer_name',
        'local_id',
        'categoria_id',
        'forma',
        'recargacheck',
        'valor',
        'status'
    ];
    protected $table = 'vendas';

    public function consumer(){
        return $this->belongsTo(Consumers::class , 'consumer_id');
    }
    public function local(){
        return $this->belongsTo(Locais::class , 'local_id');
    }
    public function categoria(){
        return $this->belongsTo(Categorias::class , 'categoria_id');
    }
}