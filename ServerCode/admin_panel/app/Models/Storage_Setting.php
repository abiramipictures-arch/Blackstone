<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Storage_Setting extends Model
{
    use HasFactory;

    protected $table = 'tbl_storage_setting';
    protected $guarded = array();

    protected $casts = [
        'id'     => 'integer',
        'key'    => 'string',
        'value'  => 'string',
        'status' => 'integer',
    ];
}
