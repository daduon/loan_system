<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class COEmployee extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'name',
        'role',
        'phone',
        'address',
        'identity',
        'picture',
        'status',
        'created_by',
        'updated_by',
    ];
}
