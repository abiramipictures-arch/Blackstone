<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;

    protected $table = 'tbl_coupon';
    protected $guarded = array();

    protected $casts = [
        'id'             => 'integer',
        'code'           => 'string',
        'title'          => 'string',
        'description'    => 'string',
        'start_date'     => 'date',
        'end_date'       => 'date',
        'discount_type'  => 'integer',
        'discount_value' => 'float',
        'applicable_for' => 'integer',
        'package_id'     => 'integer',
        'usage_limit'    => 'integer',
        'usage_per_user' => 'integer',
        'used_count'     => 'integer',
        'is_single_use'  => 'integer',
        'status'         => 'integer',
    ];
}
