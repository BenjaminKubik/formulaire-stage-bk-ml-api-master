<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Section extends model
{
    use HasFactory;

    protected $fillable = [
        'titre',
        'num_sec',
        'form_id',

    ];


    function forms() {
        return $this->belongsTo(Forms::class);
    }
    function question(){
        $this->hasMany(Question::class);
    }
}