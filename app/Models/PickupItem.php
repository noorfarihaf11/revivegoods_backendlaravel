<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PickupItem extends Model
{
    protected $primaryKey = 'id_pickupitem';
    protected $fillable = [
        'id_donationitem',
    ];
    public function pickup()
    {
        return $this->belongsTo(PickupRequest::class, 'id_pickupreq', 'id_pickupreq');
    }

    public function donationItem()
    {
        return $this->belongsTo(DonationItem::class, 'id_donationitem', 'id_donationitem');
    }
}
