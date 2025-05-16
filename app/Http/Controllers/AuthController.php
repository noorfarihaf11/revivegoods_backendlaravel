<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Log;


class AuthController extends Controller
{
    public function index()
    {
        return view('/register');
    }

    public function register(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|max:60',
            'email' => 'required|email|unique:users,email|max:200',
            'password' => 'required|min:5',
        ]);

        $validatedData['password'] = bcrypt($validatedData['password']);

        $user = User::create($validatedData);

        $token = JWTAuth::fromUser($user);

        return response()->json([
            'message' => 'Registration Successful!',
            'user' => $user,
            'token' => $token,
        ], 201);
    }

    public function login()
    {
        return view('login');
    }

    public function authenticate(Request $request)
{
    $credentials = $request->validate([
        'email' => 'required|email',
        'password' => 'required|min:5',
    ]);

    if ($token = JWTAuth::attempt($credentials)) {
        $user = Auth::user();

        if (!$user->is_active) {
            return response()->json(['error' => 'Account is deactivated'], 403);
        }

        return response()->json([
            'message' => 'Login successful',
            'token' => $token,
            'user' => [
                'id' => $user->id_user,
                'name' => $user->name,
                'email' => $user->email,
                // tambahkan data lain jika perlu
            ],
        ]);
    } else {
        return response()->json(['error' => 'Unauthorized'], 401);
    }
}



    public function logout()
    {
        try {
            JWTAuth::invalidate(JWTAuth::getToken());
    
            return response()->json(['message' => 'Successfully logged out']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to logout, token invalid'], 500);
        }
    }    


    public function deactivateAccount(Request $request)
    {
        $user = auth('api')->user();

        if (!$user) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }
        /** @var User $user */

        $user->is_active = false;
        $user->save();

        return response()->json([
            'message' => 'Account has been deactivated successfully.'
        ]);
    }
}
