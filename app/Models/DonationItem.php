<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DonationItem extends Model
{
    protected $primaryKey = 'id_donationitem';
    protected $fillable = ['id_user', 'id_category', 'name', 'description', 'image', 'status'];

    /**
     * Relasi ke User (satu user memiliki banyak DonationItem)
     */
     public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    /**
     * Relasi ke Category (satu kategori bisa memiliki banyak DonationItem)
     */
    public function category()
    {
        return $this->belongsTo(Category::class, 'id_category');  // Relasi ke Category menggunakan 'id_category'
    }
}
