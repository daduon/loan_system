<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CashTransaction extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'cash_total_usd',
        'cash_total_kh',
        'date',
        'status',
        'cash_in_desc'
    ];
}
