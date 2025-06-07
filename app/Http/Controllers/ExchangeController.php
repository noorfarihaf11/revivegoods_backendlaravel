<?php

namespace App\Http\Controllers;

use App\Models\ExchangeItem;
use App\Models\ExchangeRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExchangeController extends Controller
{
  public function redeem(Request $request)
    {
        $request->validate([
            'id_exchangeitem' => 'required|exists:exchange_items,id_exchangeitem'
        ]);

         /** @var User $user */
        $user = Auth::user();

        $exchangeItem = ExchangeItem::findOrFail($request->id_exchangeitem);

        // Cek apakah user punya cukup koin
        if ($user->coins < $exchangeItem->coin_cost) {
            return response()->json([
                'message' => 'Coin tidak cukup untuk menukar barang ini.'
            ], 400);
        }

        // Buat exchange request
        $exchange = ExchangeRequest::create([
            'id_user' => $user->id_user,
            'id_exchangeitem' => $exchangeItem->id_exchangeitem,
            'status' => $request->status, // default: pending
        ]);

        // Kurangi coin user
        $user->coins -= $exchangeItem->coin_cost;
        $user->save();

        // Ambil ulang untuk response
        $exchangeWithItem = ExchangeRequest::with('exchangeItem')->find($exchange->id);

        return response()->json([
            'message' => 'Penukaran reward berhasil diajukan.',
            'exchange_request' => [
                'id' => $exchangeWithItem->id,
                'status' => $exchangeWithItem->status,
                'item' => [
                    'id_exchangeitem' => $exchangeWithItem->exchangeItem->id_exchangeitem,
                    'name' => $exchangeWithItem->exchangeItem->name,
                    'coin_cost' => $exchangeWithItem->exchangeItem->coin_cost,
                ]
            ],
        ]);
    }
}
