<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller

{   
    
    public function register(Request $request)
    {
         try {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required',  'string', Rules\Password::defaults()],
        ]);

        // Tentukan role berdasarkan email domain
        $role = $request->email === 'admin@admin.com' ? 'admin' : 'user';

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $role 
        ]);

        event(new Registered($user));

        Auth::login($user);
        $token = $user->createToken('auth-token')->plainTextToken;

        return response()->json([
            'status' => true,
            'message' => 'Registration successful',
            'data' => [
                'user' => $user,
                'token' => $token,
                'role' => $role 
            ]
        ], 201);

    } catch (ValidationException $e) {
        return response()->json([
            'status' => false,
            'message' => 'Validation failed',
            'errors' => $e->errors()
        ], 422);
    } catch (\Exception $e) {
        return response()->json([
            'status' => false,
            'message' => 'Registration failed',
            'error' => $e->getMessage()
        ], 500);
    }
    }

 public function login(Request $request)
{
    try {
        $validator = Validator::make($request->all(), [
            'email' => [
                'required', 
                'string', 
                'email',
                'exists:users,email'  
            ],
            'password' => [
                'required', 
                'string', 
                'min:6' 
            ]
        ], [
            'email.required' => 'Email tidak boleh kosong.',
            'email.email' => 'Format email tidak valid.',
            'email.exists' => 'Email tidak terdaftar.',
            'password.required' => 'Password tidak boleh kosong.',
            'password.min' => 'Password minimal 6 karakter.'
        ]);

        // Jika validasi gagal
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'status' => false,
                'message' => 'Login gagal',
                'errors' => [
                    'email' => ['Email atau password salah']
                ]
            ], 401);
        }

        $user = $request->user();
        $token = $user->createToken('auth-token')->plainTextToken;

        $response = response()->json([
            'status' => true,
            'message' => 'Login berhasil',
            'data' => [
                'user' => $user,
                'token' => $token
            ]
        ]);

        $response->cookie(
            'token',
            $token,
            60 * 24 * 30,    
            '/',
            null,
            true,  
            true, 
            false,
            'Strict'
        );

        return $response;

    } catch (\Exception $e) {
        return response()->json([
            'status' => false,
            'message' => 'Terjadi kesalahan',
            'error' => $e->getMessage()
        ], 500);
    }
}

    
   public function logout(Request $request)
{
    try {
        // Check if user is authenticated
        if ($request->user()) {
            // Check if Sanctum token exists before deleting
            $token = $request->user()->currentAccessToken();
            if ($token) {
                $token->delete();
            }
        }
        
        // Logout from web guard
        Auth::guard('web')->logout();

        // Invalidate session
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('status', 'You have been logged out successfully');
    } catch (\Exception $e) {
    Log::error('Logout error: ' . $e->getMessage());

        return redirect()->route('login')->with('error', 'An error occurred during logout');
    }
}


public function getUser(Request $request)
{
    try {
    
        $user = $request->user();
        
        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'Unauthorized'
            ], 401);
        }

        return response()->json([
            'status' => true,
            'data' => $user
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'status' => false,
            'message' => 'Failed to get user data',
            'error' => $e->getMessage()
        ], 500);
    }
}


}