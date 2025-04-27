<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    public function donationItems()
    {
        return $this->hasMany(DonationItem::class, 'id_category');  // Relasi ke DonationItem berdasarkan 'id_category'
    }
}
