<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rent_Transaction extends Model
{
    use HasFactory;

    protected $table = 'tbl_rent_transaction';
    protected $guarded = array();

    protected $casts = [
        'id'                 => 'integer',
        'coupon_code'        => 'string',
        'user_id'            => 'integer',
        'producer_id'        => 'integer',
        'video_type'         => 'integer',
        'sub_video_type'     => 'integer',
        'video_id'           => 'integer',
        'payment_type'       => 'integer',
        'price'              => 'integer',
        'producer_earning'   => 'integer',
        'commission'         => 'integer',
        'transaction_id'     => 'string',
        'description'        => 'string',
        'expiry_date'        => 'string',
        'transaction_status' => 'integer',
        'status'             => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function producer()
    {
        return $this->belongsTo(Producer::class, 'producer_id');
    }
    public function video()
    {
        return $this->belongsTo(Video::class, 'video_id');
    }
    public function tvshow()
    {
        return $this->belongsTo(TVShow::class, 'video_id');
    }
    public static function getVideoName($video_id, $video_type, $sub_video_type)
    {
        if ($video_type == 1) {
            return Video::where('id', $video_id)->pluck('name')->first();
        } else if ($video_type == 2) {
            return TVShow::where('id', $video_id)->pluck('name')->first();
        } else if ($video_type == 6 || $video_type == 7) {

            if ($sub_video_type == 1) {
                return Video::where('id', $video_id)->pluck('name')->first();
            } else if ($sub_video_type == 2) {
                return TVShow::where('id', $video_id)->pluck('name')->first();
            }
        }
        return "-";
    }
}
