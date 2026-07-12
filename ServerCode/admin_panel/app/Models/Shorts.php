<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Producer;

class Shorts extends Model
{
    use HasFactory;

    protected $table = 'tbl_shorts';
    protected $guarded = array();

    protected $casts = [
        'id'                   => 'integer',
        'type_id'              => 'integer',
        'video_type'           => 'integer',
        'producer_id'          => 'integer',
        'category_id'          => 'string',
        'language_id'          => 'string',
        'cast_id'              => 'string',
        'name'                 => 'string',
        'storage_type'         => 'integer',
        'thumbnail'            => 'string',
        'trailer_storage_type' => 'integer',
        'trailer_type'         => 'string',
        'trailer_url'          => 'string',
        'description'          => 'string',
        'is_title'             => 'integer',
        'is_comment'           => 'integer',
        'is_like'              => 'integer',
        'total_view'           => 'integer',
        'total_like'           => 'integer',
        'avg_rating'           => 'integer',
        'total_review'         => 'integer',
        'status'               => 'integer',
    ];

    public function type()
    {
        return $this->belongsTo(Type::class, 'type_id');
    }

    public function producer()
    {
        return $this->belongsTo(Producer::class, 'producer_id');
    }
}
