<?php

namespace App\Model\Admin;

use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    //
    protected $table = 'admins';
    protected $primaryKey = 'uid';
    public $timestamps = false;

    protected  $fillable = [
      'username', 'password'
    ];

    public function questions() {
        return $this->hasMany('App\Model\Question\Question', 'uid');
    }
}
