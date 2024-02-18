<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerType extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'customer_type_name',
        'customer_type_des',
        'customer_type_rate',
        'customer_type_status',
        'customer_type_created_by',
        'customer_type_updated_by'
    ];
}
