<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class AvisDP extends Model {
    use HasFactory;

    protected $table = 'formulaire';
    public $timestamps = false;

    const NOTE = [1, 2, 3, 4, 5];

    protected $fillable = ['note'];

    function createur() {
        return $this->belongsTo(User::class);
    }

    function commentaires() {
        return $this->hasMany(Commentaire::class);
    }

    function user() {
        return $this->belongsTo(User::class);
    }
}
