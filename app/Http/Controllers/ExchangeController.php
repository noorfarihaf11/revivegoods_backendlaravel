<?php

namespace App\Http\Controllers;

use App\Models\ExchangeItem;
use Illuminate\Http\Request;

class ExchangeController extends Controller
{
    public function getExchangeItems(Request $request)
    {
        $items = ExchangeItem::all()->map(function ($item) {
            return [
                'id_exchangeitem' => $item->id_exchangeitem,
                'name' => $item->name,
                'description' => $item->description,
                'image' => $item->image,
                'coin_cost' => $item->coin_cost,
                'stock' => $item->stock
            ];
        });

        return response()->json(['items' => $items]);
    }
}
