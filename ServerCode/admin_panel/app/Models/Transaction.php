<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $table = 'tbl_transaction';
    protected $guarded = array();

    protected $casts = [
        'id'                 => 'integer',
        'coupon_code'        => 'string',
        'user_id'            => 'integer',
        'package_id'         => 'integer',
        'transaction_id'     => 'string',
        'payment_type'       => 'integer',
        'price'              => 'string',
        'description'        => 'string',
        'expiry_date'        => 'string',
        'transaction_status' => 'integer',
        'status'             => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function package()
    {
        return $this->belongsTo(Package::class, 'package_id');
    }
}
