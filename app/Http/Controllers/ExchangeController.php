<?php

namespace App\Http\Controllers;

use App\Models\ExchangeItem;
use App\Models\ExchangeRequest;
use App\Models\CoinTransaction;
use App\Models\PickupRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExchangeController extends Controller
{
  public function redeem(Request $request)
    {
        $request->validate([
            'id_exchangeitem' => 'required|exists:exchange_items,id_exchangeitem',
                'address' => 'required|string|max:255',
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

         $lastPickup = PickupRequest::where('id_user', $user->id_user)
        ->orderByDesc('created_at')
        ->first();

        // Buat exchange request
        $exchange = ExchangeRequest::create([
            'id_user' => $user->id_user,
            'id_exchangeitem' => $exchangeItem->id_exchangeitem,
            'status' => 'requested', // default: pending
             'address' => $request->address,// pastikan kolom 'address' ada
        ]);

        // Kurangi coin user
        $user->coins -= $exchangeItem->coin_cost;
        $user->save();

        CoinTransaction::create([
            'id_user' => $user->id_user,
            'type' => 'redeem',
            'amount' => $exchangeItem->coin_cost,
            'source' => 'coins',
        ]);

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

     public function index()
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json([
                'message' => 'Unauthenticated.'
            ], 401);
        }

        $exchangeRequests = ExchangeRequest::with(['user', 'exchangeItem'])
            ->where('id_user', $user->id_user)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'data' => $exchangeRequests
        ]);
    }
}
