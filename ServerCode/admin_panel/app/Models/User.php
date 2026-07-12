<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasFactory;

    protected $table = 'tbl_user';
    protected $guarded = array();

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'id'                      => 'integer',
        'user_name'               => 'string',
        'full_name'               => 'string',
        'email'                   => 'string',
        'password'                => 'string',
        'mobile_number'           => 'string',
        'storage_type'            => 'integer',
        'image_type'              => 'integer',
        'image'                   => 'string',
        'type'                    => 'integer',
        'parent_control_status'   => 'integer',
        'parent_control_password' => 'string',
        'reference_code'          => 'string',
        'wallet_amount'           => 'integer',
        'status'                  => 'integer',
    ];
}
