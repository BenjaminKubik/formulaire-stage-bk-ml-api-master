<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;
    protected $fillable = [
        'num_question',
        'libelle',
        'type',
        'form_id',
        'num_sec',
        'min',
        'max',
        'pas',
    ];


    function forms() {
        return $this->belongsTo(Forms::class);
    }
    function section(){
        $this->belongsTo(Section::class);
    }
    function textAnswer(){
        $this->hasMany(TextAnswer::class);
    }
    function intAnswer(){
        $this->hasMany(NumberAnswer::class);
    }
    function choice(){
        $this->hasMany(Choice::class);
    }
}
