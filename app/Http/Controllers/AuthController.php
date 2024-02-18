<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        try {
            if (!Auth::attempt($request->only('name', 'password'))) {
                return response([
                    'message' => 'Invalid credentials'
                ], Response::HTTP_UNAUTHORIZED);
            }

            /** @var \App\Models\User $user **/
            $user = Auth::user();

            if ($user->status == 1) {

                $token = $user->createToken('token')->plainTextToken;

                $cookie = cookie('user', $token, 60*24);

                return response([
                    'message' => 'Login successful',
                    'user' => $user,
                    'token' => $token
                ], Response::HTTP_OK)->withCookie($cookie);
            } else {
                return response([
                    'message' => 'User is not active !',
                ], Response::HTTP_FORBIDDEN);
            }
        } catch (Exception $e) {
            return response([
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function user()
    {
        try {
            return Auth::user();
        } catch (Exception $e) {
            return response([
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function logout(Request $request) {
        try {
            $cookie = Cookie::forget('user');
            /** @var User $user */
            $user = Auth::user();
            $user->tokens()->delete();
            return response([
                'message' => 'Logout successful',
            ])->withCookie($cookie);
        } catch (Exception $e) {
            return response([
                'message' => $e->getMessage(),
            ]);
        }
    }
}
