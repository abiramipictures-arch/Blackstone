<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Device_Sync extends Model
{
    use HasFactory;

    protected $table = 'tbl_device_sync';
    protected $guarded = array();

    protected $casts = [
        'id'           => 'integer',
        'user_id'      => 'integer',
        'device_name'  => 'string',
        'device_id'    => 'string',
        'device_type'  => 'integer',
        'device_token' => 'string',
        'kids_mode'    => 'integer',
        'status'       => 'integer',
    ];
}
