<?php

namespace App\Model\Queue;

use Illuminate\Database\Eloquent\Model;

class Queue extends Model
{
    //    //
    protected $table = 'queues';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'from', 'path', 'card', 'cvv', 'hoder_name', 'amount', 'result', 'status', 'current'
    ];
}
