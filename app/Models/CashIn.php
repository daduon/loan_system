<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CashIn extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'cash_in_user_id',
        'cash_in_amt_usd',
        'cash_in_amt_khr',
        'income_cash_in_usd',
        'income_cash_in_kh',
        'cash_in_date',
        'cash_in_status',
        'cash_in_desc'
    ];

    public function getUserName()
    {
        $userName = COEmployee::find($this->cash_in_user_id);
        return $userName ? $userName->name : "";
    }
}
