<?php

namespace App;
use Illuminate\Database\Eloquent\Model;

class Consumers extends Model
{
    protected $fillable = [
        'name',
        'cpf',
        'rg',
        'nis',
        'sexo',
        'nascimento',
        'email',
        'categoria_id',
        'cep',
        'complement',
        'number',
        'dependentes',
        'numdepen',
        'renda',
        'saldo',
        'status'
    ];
    protected $table = 'consumers';

    public function categoria(){
        return $this->belongsTo(Categorias::class, 'categoria_id');
    }
}
