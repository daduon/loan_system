<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'expense_no',
        'expense_desc',
        'expense_date',
        'expense_by',
        'expense_status',
        'expense_amount_usd',
        'expense_amount_kh',
        'updated_at'
    ];

    public function getUserName()
    {
        $userName = COEmployee::find($this->expense_by);
        return $userName ? $userName->name : "";
    }
}
