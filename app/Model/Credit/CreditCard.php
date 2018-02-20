<?php

namespace App\Model\Credit;

use Illuminate\Database\Eloquent\Model;

class CreditCard extends Model
{
    //
    protected $table = 'creditcards';
    protected $primaryKey = 'cardId';
    public $timestamps = false;

    protected $fillable = [
      'csc', 'expireDate', 'accountid'
    ];

    public function account() {
        $this->belongsTo('App\Model\Credit\CreditAccount', 'accountid', 'cardId');
    }
}
