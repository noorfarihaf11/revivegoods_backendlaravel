<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExchangeRequest extends Model
{

    protected $primaryKey = 'id';
    protected $fillable = ['id_user', 'id_exchangeitem', 'status','address'];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function exchangeItem()
    {
        return $this->belongsTo(ExchangeItem::class, 'id_exchangeitem');
    }
}
