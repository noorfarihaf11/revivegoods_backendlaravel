<?php

namespace App\Http\Controllers;

use App\Models\PickupItem;
use App\Models\User;
use App\Models\PickupRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PickupRequestController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.id_donationitem' => 'required|integer|exists:donation_items,id_donationitem',
            'scheduled_at' => 'required|date_format:Y-m-d H:i:s',
            'address' => 'required|string|max:255',
            'total_coins' => 'required|integer|min:0',
        ]);

        /** @var User $user */
        $user = Auth::user();

        // Simpan pickup request
        $pickup = PickupRequest::create([
            'id_user' => $user->id_user,
            'scheduled_at' => $request->scheduled_at,
            'status' => 'requested',
            'address' => $request->address,
            'total_coins' => $request->total_coins, // dari frontend
        ]);

        // Simpan item-item
        foreach ($request->items as $item) {
            $pickup->items()->create([
                'id_donationitem' => $item['id_donationitem'],
            ]);
        }

        // Ambil ulang untuk response
        $pickupWithItems = PickupRequest::with(['items.donationItem'])->find($pickup->id_pickupreq);

        return response()->json([
            'message' => 'Pickup request submitted successfully.',
            'pickup_request' => [
                'id_pickupreq' => $pickupWithItems->id_pickupreq,
                'status' => $pickupWithItems->status,
                'scheduled_at' => $pickupWithItems->scheduled_at,
                'address' => $pickupWithItems->address,
                'total_coins' => $pickupWithItems->total_coins,
                'items' => $pickupWithItems->items->map(function ($item) {
                    return [
                        'id_donationitem' => $item->id_donationitem,
                        'name' => $item->donationItem->name ?? '(Unknown)',
                    ];
                }),
            ],
        ]);
    }

    public function getPickupData(Request $request)
    {
        $id_user = Auth::id();  // ambil id user yang login

        $pickupData = PickupRequest::where('id_user', $id_user)->get()->map(function ($pickup) {
            return [
                'id_pickupreq' => $pickup->id_pickupreq,
                'scheduled_at' => $pickup->scheduled_at,
                'address' => $pickup->address,
                'status' => $pickup->status,
            ];
        });

        return response()->json(['pickupData' => $pickupData]);
    }
}
