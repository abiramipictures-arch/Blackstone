<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserInterest extends Model
{
    use HasFactory;

    protected $table = 'tbl_user_interest';
    protected $guarded = array();

    protected $casts = [
        'id'          => 'integer',
        'user_id'     => 'integer',
        'category_id' => 'integer',
        'watch_count' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
