<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PickupRequest extends Model
{

    protected $primaryKey = 'id_pickupreq';
    protected $fillable = [
        'id_user',
        'scheduled_at',
        'status',
        'address',
        'total_coins',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime:Y-m-d H:i:s',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(PickupItem::class, 'id_pickupreq', 'id_pickupreq');
    }
}
