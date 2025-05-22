<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CoinTransaction extends Model
{
    protected $table = 'coin_transactions';

    protected $fillable = [
       'id', 'id_user', 'type', 'amount', 'source',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
