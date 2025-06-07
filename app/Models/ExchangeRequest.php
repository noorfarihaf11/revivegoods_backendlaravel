<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExchangeRequest extends Model
{

    protected $primaryKey = 'id';
    protected $fillable = ['id_user', 'id_exchangeitem', 'status'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function exchangeItem()
    {
        return $this->belongsTo(ExchangeItem::class, 'id_exchangeitem');
    }
}
