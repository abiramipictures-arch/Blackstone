<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cast extends Model
{
    use HasFactory;

    protected $table = 'tbl_cast';
    protected $guarded = array();

    protected $casts = [
        'id'            => 'integer',
        'name'          => 'string',
        'storage_type'  => 'integer',
        'image'         => 'string',
        'type'          => 'string',
        'personal_info' => 'string',
        'status'        => 'integer',
    ];
}
