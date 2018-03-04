<?php

namespace App\Model\Station;

use Illuminate\Database\Eloquent\Model;

class Station extends Model
{
    //
    protected $table = 'station';
    protected $primaryKey = 'id';

    protected $fillable = [
        'status', 'ip', 'type'
    ];
}
