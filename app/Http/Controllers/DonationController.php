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
        $items = DonationItem::all();

        return response()->json([
            'items' => $items,
        ], 200);
    }

    // public function store(Request $request)
    // {
    //     $validated = $request->validate([
    //         'name' => 'required|string',
    //         'description' => 'nullable|string',
    //         'category_id' => 'required|exists:categories,id',
    //     ]);

    //     $donation = Auth::user()->donationItems()->create($validated);

    //     return response()->json($donation, 201);
    // }

    // public function show($id)
    // {
    //     $donation = DonationItem::with('category')->findOrFail($id);

    //     if ($donation->user_id !== Auth::id()) {
    //         return response()->json(['error' => 'Unauthorized'], 403);
    //     }

    //     return $donation;
    // }
}

