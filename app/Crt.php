<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Crt extends Model
{
    protected $fillable = [
        'consumer_id',
        'local_id',
        'categoria_id'
    ];
    protected $table = 'crt';

    public function categoria(){
        return $this->belongsTo(Categorias::class, 'categoria_id');
    }
    public function local(){
        return $this->belongsTo(Locais::class, 'local_id');
    }
    public function consumer(){
        return $this->belongsTo(Consumers::class, 'consumer_id');
    }
}
