<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Choice extends Model
{
    use HasFactory;
    protected $fillable = [
        'choice',
        'num_question',
        'num_sec',
        'form_id',
    ];

    function question(){
        $this->belongsTo(Question::class);
    }
}
