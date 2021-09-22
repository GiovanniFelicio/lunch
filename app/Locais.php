<?php

namespace App;
use Illuminate\Database\Eloquent\Model;

class Locais extends Model
{
    protected $fillable = [
        'name', 
        'email',
        'status'
    ];
    protected $table = 'locais';

    public function users(){
        return $this->hasMany(User::class, 'local_id');
    }
}
