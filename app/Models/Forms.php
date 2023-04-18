<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Forms extends Model
{
    use HasFactory;
    protected $fillable = [
        'titre',
        'nbSec',
        'prive'
    ];

    function questions() {
        return $this->hasMany(Question::class);
    }

    function sections(){
        return $this->hasMany(Section::class);
    }

}
