<?php

namespace App\Model\Credit;

use Illuminate\Database\Eloquent\Model;

class CreditAccount extends Model
{
    //
    protected $table = 'creditaccounts';
    protected $primaryKey = 'accountid';
    public $timestamps = false;

    protected $fillable = [
        'holdername', 'phonenumber', 'address', 'spendlinglimit', 'balance'
    ];

    public function cards() {
        return $this->hasMany('App\Model\Credit\CreditCard', 'accountid');
    }
}
