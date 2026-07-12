<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TVShow_Video extends Model
{
    use HasFactory;

    protected $table = 'tbl_tv_show_video';
    protected $guarded = array();

    protected $casts = [
        'id'                    => 'integer',
        'show_id'               => 'integer',
        'season_id'             => 'integer',
        'name'                  => 'string',
        'storage_type'          => 'integer',
        'thumbnail'             => 'string',
        'landscape'             => 'string',
        'description'           => 'string',
        'video_storage_type'    => 'integer',
        'video_upload_type'     => 'string',
        'video_320'             => 'string',
        'video_480'             => 'string',
        'video_720'             => 'string',
        'video_1080'            => 'string',
        'video_extension'       => 'string',
        'video_duration'        => 'integer',
        'subtitle_storage_type' => 'integer',
        'subtitle_type'         => 'string',
        'subtitle_lang_1'       => 'string',
        'subtitle_1'            => 'string',
        'subtitle_lang_2'       => 'string',
        'subtitle_2'            => 'string',
        'subtitle_lang_3'       => 'string',
        'subtitle_3'            => 'string',
        'is_premium'            => 'integer',
        'is_title'              => 'integer',
        'is_download'           => 'integer',
        'total_view'            => 'integer',
        'sort_order'            => 'integer',
        'status'                => 'integer',
    ];

    public function season()
    {
        return $this->belongsTo(Season::class, 'season_id');
    }
}
