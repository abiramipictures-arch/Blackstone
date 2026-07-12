<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Season extends Model
{
    use HasFactory;

    protected $table = 'tbl_season';
    protected $guarded = array();

    protected $casts = [
        'id'         => 'integer',
        'name'       => 'string',
        'sort_order' => 'integer',
        'status'     => 'integer',
    ];
}
