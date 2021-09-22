<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cad extends Model
{
    protected $fillable = [
        'name',
        'nis',
        'sexo',
        'nascimento',
        'uf_nasc',
        'cpf',
        'rg',
        'dt_emissao',
        'uf_emissao'
    ];
    protected $table = 'cad';
}
