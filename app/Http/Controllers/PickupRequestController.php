<?php

namespace App\Http\Controllers;

use App\Models\PickupItem;
use App\Models\User;
use App\Models\CoinTransaction;
use App\Models\PickupRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;


class PickupRequestController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.id_donationitem' => 'required|integer|exists:donation_items,id_donationitem',
            'scheduled_at' => 'required|date',
            'address' => 'required|string|max:255',
            'total_coins' => 'required|integer|min:0',
        ]);

        /** @var User $user */
        $user = Auth::user();

        // Simpan pickup request
        $pickup = PickupRequest::create([
            'id_user' => $user->id_user,
            'scheduled_at' => Carbon::parse($request->scheduled_at)->setTimezone('UTC'),
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
        $pickupWithItems = PickupRequest::with(['items.donationItem'])
            ->findOrFail($pickup->id_pickupreq);


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
        $user = Auth::user();

        $pickupList = PickupRequest::where('id_user', $user->id_user)->get();

        foreach ($pickupList as $pickup) {
            if (
                $pickup->status === 'requested' &&
                $pickup->scheduled_at &&
                Carbon::now()->greaterThanOrEqualTo(Carbon::parse($pickup->scheduled_at)->addHours(3))
            ) {
                $pickup->status = 'completed';
                $pickup->save();

                $alreadyRewarded = CoinTransaction::where([
                    ['id_user', '=', $pickup->id_user],
                    ['source', '=', 'pickup'],
                    ['amount', '=', $pickup->total_coins],
                ])->exists();

                if (!$alreadyRewarded) {
                    CoinTransaction::create([
                        'id_user' => $pickup->id_user,
                        'type' => 'earn',
                        'amount' => $pickup->total_coins,
                        'source' => 'pickup',
                    ]);

                    $user = User::find($pickup->id_user);
                    if ($user) {
                        $user->coins += $pickup->total_coins;
                        $user->save();
                    }
                }
            }
        }

        $pickupData = $pickupList->map(function ($pickup) {
            // Pastikan scheduled_at tidak dikonversi ke zona waktu lain
            return [
                'id_pickupreq' => $pickup->id_pickupreq,
               'scheduled_at' => (string) $pickup->scheduled_at,
                'address' => $pickup->address,
                'status' => $pickup->status,
                'total_coins' => $pickup->total_coins,
            ];
        })->toArray();

        Log::info('Pickup data response: ' . json_encode($pickupData));
        return response()->json(['pickupData' => $pickupData]);
    }
}
