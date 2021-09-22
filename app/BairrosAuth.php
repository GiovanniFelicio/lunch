<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BairrosAuth extends Model
{
    protected $fillable = [
        'bairro_id',
        'local_id',
        'status'
    ];
    protected $table = 'bairros_auth';

    public function bairros(){
        return $this->belongsTo(Bairros::class , 'bairro_id');
    }
    public function locais(){
        return $this->belongsTo(Locais::class, 'local_id');
    }
}