<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function getUserData()
    {
        $user = Auth::user();

        return response()->json([
            'id_user' => $user->id_user,
            'name' => $user->name,
            'email' => $user->email,
            'coins' => $user->coins,
        ]);
    }
}
