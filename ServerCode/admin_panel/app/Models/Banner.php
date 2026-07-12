<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    use HasFactory;

    protected $table = 'tbl_banner';
    protected $guarded = array();

    protected $casts = [
        'id'             => 'integer',
        'is_home_screen' => 'integer',
        'type_id'        => 'integer',
        'video_type'     => 'integer',
        'subvideo_type'  => 'integer',
        'video_id'       => 'integer',
        'sort_order'     => 'integer',
        'status'         => 'integer',
    ];

    public function type()
    {
        return $this->belongsTo(Type::class, 'type_id');
    }
}
