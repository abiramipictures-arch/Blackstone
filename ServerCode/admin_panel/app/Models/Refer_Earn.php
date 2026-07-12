<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Refer_Earn extends Model
{
    protected $table = 'tbl_refer_earn';
    protected $guarded = [];

    protected $casts = [
        'id'             => 'integer',
        'reference_code' => 'string',
        'parent_user_id' => 'integer',
        'child_user_id'  => 'integer',
        'parent_earn'    => 'integer',
        'child_earn'     => 'integer',
    ];

    public function parent_user()
    {
        return $this->belongsTo(User::class, 'parent_user_id');
    }
    public function child_user()
    {
        return $this->belongsTo(User::class, 'child_user_id');
    }
}
