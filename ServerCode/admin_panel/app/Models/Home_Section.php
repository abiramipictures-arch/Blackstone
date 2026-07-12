<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Home_Section extends Model
{
    use HasFactory;

    protected $table = 'tbl_home_section';
    protected $guarded = array();

    protected $casts = [
        'id'              => 'integer',
        'section_type'    => 'integer',
        'is_home_screen'  => 'integer',
        'video_type'      => 'integer',
        'sub_video_type'  => 'integer',
        'type_id'         => 'integer',
        'title'           => 'string',
        'short_title'     => 'string',
        'screen_layout'   => 'string',
        'content_ids'     => 'string',
        'category_id'     => 'integer',
        'language_id'     => 'integer',
        'channel_id'      => 'integer',
        'order_by_upload' => 'integer',
        'order_by_view'   => 'integer',
        'premium_video'   => 'integer',
        'no_of_content'   => 'integer',
        'view_all'        => 'integer',
        'is_title'        => 'integer',
        'sort_order'      => 'integer',
        'status'          => 'integer',
    ];
}
