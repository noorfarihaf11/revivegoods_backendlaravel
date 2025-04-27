<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PickupItem extends Model
{
    public function pickup()
    {
        return $this->belongsTo(PickupRequest::class, 'pickup_id');
    }

    public function donationItem()
    {
        return $this->belongsTo(DonationItem::class, 'donation_item_id');
    }
}
