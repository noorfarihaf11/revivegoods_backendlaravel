<?php

namespace App\Http\Controllers;

use App\Models\PickupItem;
use App\Models\User;
use App\Models\CoinTransaction;
use App\Models\PickupRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class PickupRequestController extends Controller
{
   public function store(Request $request)
{
    $request->validate([
        'items'                       => 'required|array|min:1',
        'items.*.id_donationitem'     => 'required|integer|exists:donation_items,id_donationitem',
        'scheduled_at'                => 'required|date',
        'address'                     => 'required|string|max:255',
        'total_coins'                 => 'required|integer|min:0',
    ]);

    /** @var User $user */
    $user = Auth::user();

    $scheduledAt = Carbon::parse($request->scheduled_at);  // WIB

    $pickup = DB::transaction(function () use ($user, $request, $scheduledAt) {

        // 1️⃣  Buat pickup request
        $pickup = PickupRequest::create([
            'id_user'       => $user->id_user,
            'scheduled_at'  => $scheduledAt,
            'status'        => 'completed',          // langsung completed
            'address'       => $request->address,
            'total_coins'   => $request->total_coins,
        ]);

        // 2️⃣  Simpan item-item
        foreach ($request->items as $item) {
            $pickup->items()->create([
                'id_donationitem' => $item['id_donationitem'],
            ]);
        }

        // 3️⃣  Tambahkan koin user (atomic)
        $user->increment('coins', $pickup->total_coins);

        return $pickup;   // penting: kembalikan untuk dipakai di luar
    });

    // Ambil pickup + relasi untuk response
    $pickup->load('items.donationItem');

    return response()->json([
        'message'        => 'Pickup request submitted successfully.',
        'new_coin_total' => $user->coins,                 // saldo baru
        'pickup_request' => [
            'id_pickupreq' => $pickup->id_pickupreq,
            'status'       => $pickup->status,
            'scheduled_at' => Carbon::parse($pickup->scheduled_at)
                                   ->timezone('Asia/Jakarta')
                                   ->toDateTimeString(),
            'address'      => $pickup->address,
            'total_coins'  => $pickup->total_coins,
            'items'        => $pickup->items->map(fn ($i) => [
                'id_donationitem' => $i->id_donationitem,
                'name'            => $i->donationItem->name ?? '(Unknown)',
            ]),
        ],
    ]);
}

    public function getPickupData(Request $request)
    {
        $user = Auth::user();

        $pickupList = PickupRequest::where('id_user', $user->id_user)->get();

        foreach ($pickupList as $pickup) {
            // Gunakan waktu UTC dan bandingkan dengan sekarang (juga dalam UTC)
            $scheduledAtUtc = Carbon::parse($pickup->scheduled_at);
            $nowUtc = Carbon::now('UTC');

            if (
                $pickup->status === 'requested' &&
                $scheduledAtUtc &&
                $nowUtc->greaterThanOrEqualTo($scheduledAtUtc->addHours(3))
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
            return [
                'id_pickupreq' => $pickup->id_pickupreq,
                'scheduled_at' => Carbon::parse($pickup->scheduled_at)
                    ->timezone('Asia/Jakarta') // Tambahkan ini!
                    ->toIso8601String(), // Jadi: kirim dengan zona waktu +07:00
                'address' => $pickup->address,
                'status' => $pickup->status,
                'total_coins' => $pickup->total_coins,
            ];
        })->toArray();


        Log::info('Pickup data response: ' . json_encode($pickupData));

        return response()->json(['pickupData' => $pickupData]);
    }
}
