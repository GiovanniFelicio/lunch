<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bairros extends Model
{
    protected $fillable = [
        'name',
    ];
    protected $table = 'bairros';
}
