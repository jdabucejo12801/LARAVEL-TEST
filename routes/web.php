<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/setup', function () {
    $credentials = [
         'email' => 'admin@admin.com',
         'password' => 'password',
    ];

    // Ensure the user exists
    $user = \App\Models\User::where('email', $credentials['email'])->first();
    if (!$user) {
        $user = new \App\Models\User();
        $user->name = 'Admin';
        $user->email = $credentials['email'];
        $user->password = Hash::make($credentials['password']);
        $user->save();
    }

    // Attempt to authenticate
    if (!Auth::attempt($credentials)) {
        return response()->json(['message' => 'Authentication failed'], 500);
    }

    $user = Auth::user();

    // Optionally revoke previous tokens to avoid clutter
    // $user->tokens()->delete();

    $adminToken = $user->createToken('admin-token', ['create', 'update', 'delete'])->plainTextToken;
    $updateToken = $user->createToken('update-token', ['create', 'update'])->plainTextToken;
    $basicToken = $user->createToken('basic-token')->plainTextToken;

    return response()->json([
        'admin' => $adminToken,
        'update' => $updateToken,
        'basic' => $basicToken,
    ]);
});
