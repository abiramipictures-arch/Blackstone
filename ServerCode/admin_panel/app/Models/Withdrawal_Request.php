<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Withdrawal_Request extends Model
{
    use HasFactory;

    protected $table = 'tbl_withdrawal_request';
    protected $guarded = array();

    protected $casts = [
        'id'          => 'integer',
        'producer_id' => 'integer',
        'price'       => 'integer',
        'status'      => 'integer',
    ];

    public function producer()
    {
        return $this->belongsTo(Producer::class, 'producer_id');
    }
}
