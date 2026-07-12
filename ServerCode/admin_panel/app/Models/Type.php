<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Type extends Model
{
    use HasFactory;

    protected $table = 'tbl_type';
    protected $guarded = array();

    protected $casts = [
        'id'           => 'integer',
        'name'         => 'string',
        'type'         => 'integer',
        'storage_type' => 'integer',
        'icon'         => 'string',
        'sort_order'   => 'integer',
        'status'       => 'integer',
    ];
}
