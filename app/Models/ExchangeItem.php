<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExchangeItem extends Model
{
    protected $primaryKey = 'id_exchangeitem';
    protected $fillable = ['name', 'description', 'image', 'coin_cost', 'stock'];

    public function exchangeRequests()
    {
        return $this->hasMany(ExchangeRequest::class, 'exchange_item_id');
    }
}
