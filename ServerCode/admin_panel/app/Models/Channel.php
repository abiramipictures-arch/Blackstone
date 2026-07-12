<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Channel extends Model
{
    use HasFactory;

    protected $table = 'tbl_channel';
    protected $guarded = array();

    protected $casts = [
        'id'            => 'integer',
        'name'          => 'string',
        'storage_type'  => 'integer',
        'portrait_img'  => 'string',
        'landscape_img' => 'string',
        'is_title'      => 'integer',
        'status'        => 'integer',
    ];
}
