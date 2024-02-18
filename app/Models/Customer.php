<?php

namespace App\Models;

use GuzzleHttp\Promise\Create;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'customer_type_id',
        'customer_name',
        'customer_email',
        'customer_gender',
        'customer_picture',
        'customer_dob',
        'customer_address',
        'customer_phone',
        'isdiscount',
        'customer_status',
        'customer_identity',
        'customer_created_by',
        'customer_updated_by',
    ];

    protected $casts = [
        'id' => 'string',
    ];

    protected static function boot() {
        parent::boot();

        self::creating(function($model) {
            $lastId = DB::table('customers')->max('id');
            $nextId = $lastId ? (int)substr($lastId, 4) + 1 : 1;
            $model->id = 'CST-' . str_pad($nextId, 10, '0', STR_PAD_LEFT);
        });
    }
}
