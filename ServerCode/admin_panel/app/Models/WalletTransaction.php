<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WalletTransaction extends Model
{
    use HasFactory;

    protected $table = 'tbl_wallet_transaction';
    protected $guarded = array();

    protected $casts = [
        'id'             => 'integer',
        'user_id'        => 'integer',
        'amount'         => 'integer',
        'transaction_id' => 'string',
        'description'    => 'string',
        'status'         => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
