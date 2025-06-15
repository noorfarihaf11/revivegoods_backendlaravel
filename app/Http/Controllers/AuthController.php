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
use Exception;


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

        $token = $user->createToken('ReviveGoods')->plainTextToken;

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
    return response()->json(['message' => 'Login route reached']);
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
