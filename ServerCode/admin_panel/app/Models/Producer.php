<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Producer extends Authenticatable
{
    use HasFactory;

    protected $table = 'tbl_producer';
    protected $guarded = array();

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'id'            => 'integer',
        'user_name'     => 'string',
        'full_name'     => 'string',
        'email'         => 'string',
        'password'      => 'string',
        'mobile_number' => 'string',
        'storage_type'  => 'integer',
        'image'         => 'string',
        'wallet'        => 'integer',
        'status'        => 'integer',
    ];
}
