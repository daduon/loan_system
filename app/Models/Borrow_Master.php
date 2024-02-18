<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Borrow_Master extends Model
{
    protected $table = 'borrow_masters';
    // protected $primaryKey = 'id';
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'coemployee_id',
        'borrowingTypeCode',
        'currencyCode',
        'loanAmount',
        'maturityDate',
        'startDate',
        'applyInterestRate',
        'remarkDesc',
        'applyInterestRate',
        'ispaid',
        'numofmonth'
    ];

    public $timestamps = false;
}
