<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    use HasFactory;

    protected $table = 'tbl_like';
    protected $guarded = array();

    protected $casts = [
        'id'             => 'integer',
        'user_id'        => 'integer',
        'video_type'     => 'integer',
        'sub_video_type' => 'integer',
        'video_id'       => 'integer',
        'status'         => 'integer',
    ];
}
