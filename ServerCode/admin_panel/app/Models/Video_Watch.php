<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Video_Watch extends Model
{
    use HasFactory;

    protected $table = 'tbl_video_watch';
    protected $guarded = array();

    protected $casts = [
        'id'              => 'integer',
        'user_id'         => 'integer',
        'is_kids_profile' => 'integer',
        'video_type'      => 'integer',
        'sub_video_type'  => 'integer',
        'video_id'        => 'integer',
        'episode_id'      => 'integer',
        'stop_time'       => 'integer',
        'status'          => 'integer',
    ];
}
