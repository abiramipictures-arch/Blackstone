<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Producer;

class Video extends Model
{
    use HasFactory;

    protected $table = 'tbl_video';
    protected $guarded = array();

    protected $casts = [
        'id'                    => 'integer',
        'type_id'               => 'integer',
        'video_type'            => 'integer',
        'channel_id'            => 'integer',
        'producer_id'           => 'integer',
        'category_id'           => 'string',
        'language_id'           => 'string',
        'cast_id'               => 'string',
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
        'trailer_storage_type'  => 'integer',
        'trailer_type'          => 'string',
        'trailer_url'           => 'string',
        'subtitle_storage_type' => 'integer',
        'subtitle_type'         => 'string',
        'subtitle_lang_1'       => 'string',
        'subtitle_1'            => 'string',
        'subtitle_lang_2'       => 'string',
        'subtitle_2'            => 'string',
        'subtitle_lang_3'       => 'string',
        'subtitle_3'            => 'string',
        'release_date'          => 'string',
        'is_premium'            => 'integer',
        'is_title'              => 'integer',
        'is_download'           => 'integer',
        'is_comment'            => 'integer',
        'is_like'               => 'integer',
        'is_rent'               => 'integer',
        'price'                 => 'integer',
        'rent_day'              => 'integer',
        'avg_rating'            => 'float',
        'total_review'          => 'integer',
        'total_view'            => 'integer',
        'total_like'            => 'integer',
        'status'                => 'integer',
    ];

    public function type()
    {
        return $this->belongsTo(Type::class, 'type_id');
    }
    public function producer()
    {
        return $this->belongsTo(Producer::class, 'producer_id');
    }
    public function rent_price_list()
    {
        return $this->belongsTo(Rent_Price_List::class, 'price');
    }
}
