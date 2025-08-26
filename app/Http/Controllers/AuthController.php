<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;    

class AuthController extends Controller
{
    //
    public function index()
    {
        return response()->json(['message' => 'Welcome to the dashboard!'], 200);
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'User registered successfully!',
            'user' => $user,
            'token' => $token
        ], 201);
    }
    public function login(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string|min:8',
        ]);

        if (auth()->attempt($validated)) {
            $user = auth()->user();
            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'message' => 'User logged in successfully!',
                'user' => $user,
                'token' => $token
            ], 200);
        }

        return response()->json(['message' => 'Invalid credentials'], 401);
    }
    public function logout(Request $request)
    {
        auth()->logout();

        return response()->json(['message' => 'User logged out successfully!'], 200);
    }
}

