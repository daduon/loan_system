<?php

namespace App\Enums;

enum CashTransactionType: string
{
    case OTHER = 'Others';
    case LOAN = 'From Loan';
}
