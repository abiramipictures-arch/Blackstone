<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    use HasFactory;

    protected $table = 'tbl_package';
    protected $guarded = array();

    protected $casts = [
        'id'                      => 'integer',
        'package_type'            => 'integer',
        'name'                    => 'string',
        'price'                   => 'double',
        'type'                    => 'string',
        'time'                    => 'string',
        'watch_on_laptop_tv'      => 'string',
        'ads_free_content'        => 'integer',
        'no_of_device_sync'       => 'integer',
        'android_product_package' => 'string',
        'ios_product_package'     => 'string',
        'web_product_package'     => 'string',
        'status'                  => 'integer',
    ];
}
