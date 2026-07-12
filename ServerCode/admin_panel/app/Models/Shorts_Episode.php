<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shorts_Episode extends Model
{
    use HasFactory;

    protected $table = 'tbl_shorts_episode';
    protected $guarded = array();

    protected $casts = [
        'id'                 => 'integer',
        'show_id'            => 'integer',
        'season_id'          => 'integer',
        'name'               => 'string',
        'storage_type'       => 'integer',
        'thumbnail'          => 'string',
        'description'        => 'string',
        'video_storage_type' => 'integer',
        'video_upload_type'  => 'string',
        'video_320'          => 'string',
        'video_duration'     => 'integer',
        'is_premium'         => 'integer',
        'is_title'           => 'integer',
        'total_view'         => 'integer',
        'sort_order'         => 'integer',
        'status'             => 'integer',
    ];

    public function season()
    {
        return $this->belongsTo(Season::class, 'season_id');
    }
}
