<?php

namespace App\Model\Question;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    //
    protected $table = 'questions';
    protected $primaryKey = 'qid';
    public $timestamps = false;

    protected $fillable = [
        'question', 'content'
    ];

    public function user() {
        return $this->belongsTo('App\Model\Admin\Admin', 'qid', 'uid');
    }
}
