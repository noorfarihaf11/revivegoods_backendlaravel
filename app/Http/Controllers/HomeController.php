<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function getHomeData()
    {
        $user = Auth::user();
        $items = \App\Models\ExchangeItem::all()->map(function ($item) {
            return [
                'id_exchangeitem' => $item->id_exchangeitem,
                'name' => $item->name,
                'description' => $item->description,
                'image' => $item->image,
                'coin_cost' => $item->coin_cost,
                'stock' => $item->stock
            ];
        });

        return response()->json([
            'user' => [
                'id_user' => $user->id_user,
                'name' => $user->name,
                'email' => $user->email,
                'coins' => $user->coins,
            ],
            'exchange_items' => $items
        ]);
    }
}
