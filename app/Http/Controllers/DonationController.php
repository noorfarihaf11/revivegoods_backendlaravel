<?php

namespace App\Http\Controllers;

use App\Models\DonationItem;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DonationController extends Controller
{
    public function getDonationItems(Request $request)
    {
        $items = DonationItem::with('category')->get()->map(function ($item) {
            return [
                'id_donationitem' => $item->id_donationitem,
                'name' => $item->name,
                'coins' => $item->coins,
                'image' => $item->image,
                'category_name' => $item->category->name ?? 'Unknown',
            ];
        });
    
        return response()->json(['items' => $items]);
    }

}