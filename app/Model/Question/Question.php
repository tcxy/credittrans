<?php

namespace App\Model\Question;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    //
    protected $table = 'questions';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'question', 'content'
    ];
}
