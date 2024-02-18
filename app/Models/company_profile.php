<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class company_profile extends Model
{
    protected $primaryKey = 'company_profile_id';
    protected $fillable = [
        'company_profile_name',
        'company_profile_phone',
        'company_profile_email',
        'company_profile_address',
        'company_profile_wifi',
        'company_profile_set_up_point',
        'company_profile_image'
    ];
}
