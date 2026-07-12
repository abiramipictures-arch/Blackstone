<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Device_Watching extends Model
{
    use HasFactory;

    protected $table = 'tbl_device_watching';
    protected $guarded = array();

    protected $casts = [
        'id'        => 'integer',
        'user_id'   => 'integer',
        'device_id' => 'string',
        'status'    => 'integer',
    ];

    public function device_sync()
    {
        return $this->belongsTo(Device_Sync::class, 'device_id', 'device_id');
    }
}
