<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rent_Price_List extends Model
{
    use HasFactory;

    protected $table = 'tbl_rent_price_list';
    protected $guarded = array();

    protected $casts = [
        'id'                      => 'integer',
        'price'                   => 'double',
        'android_product_package' => 'string',
        'ios_product_package'     => 'string',
        'web_price_id'            => 'string',
        'status'                  => 'integer',
    ];
}
