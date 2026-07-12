<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $table = 'tbl_review';
    protected $guarded = [];

    protected $casts = [
        'id'             => 'integer',
        'user_id'        => 'integer',
        'video_type'     => 'integer',
        'sub_video_type' => 'integer',
        'video_id'       => 'integer',
        'rating'         => 'integer',
        'review_text'    => 'string',
        'status'         => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    public function video()
    {
        return $this->belongsTo(Video::class, 'video_id', 'id');
    }
    public function tvshow()
    {
        return $this->belongsTo(TVShow::class, 'video_id', 'id');
    }
    public function shorts()
    {
        return $this->belongsTo(Shorts::class, 'video_id', 'id');
    }
}
