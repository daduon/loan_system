<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Borrow_Schedule extends Model
{
    protected $table        = 'borrow_schedules';
    use HasFactory;

    protected $fillable = [
        'borrowing_id',
        'paymentapplydate',
        'taxamount',
        'transactioninterestamount',
        'transactionprincipal',
        'paymentfromdate',
        'paymentTodate',
        'ledgerstatuscode',
        'schedulestatuscode',
        'applyinterestrate',
        'repaytaxamount',
        'scheduleseqno',
        'repayinterest',
        'repayprincipal',
        'calc',
    ];

    public $timestamps = false;
}
