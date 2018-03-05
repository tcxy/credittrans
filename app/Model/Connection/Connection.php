<?php

namespace App\Model\Connection;

use Illuminate\Database\Eloquent\Model;

class Connection extends Model
{
    //
    protected $table = 'connections';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'weight', 'from', 'to'
    ];
}
