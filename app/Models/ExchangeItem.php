<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExchangeItem extends Model
{
    public function exchangeRequests()
    {
        return $this->hasMany(ExchangeRequest::class, 'exchange_item_id');
    }
}
