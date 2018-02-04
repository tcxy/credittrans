<?php

namespace App\Model\Admin;

use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    //
    protected $table = 'admin';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected  $fillable = [
      'username', 'password'
    ];
}
