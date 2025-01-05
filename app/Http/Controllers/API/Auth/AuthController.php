<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Login
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $validated = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        if (!$validated) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation error',
                'error' => $validated,
            ], 401);
        }

        if (Auth::attempt(['username' => $request->username, 'password' => $request->password])) {
            /** @var \App\Models\User $user **/
            $user = Auth::user();
            $accessToken = $user->createToken('appToken')->accessToken;
            return response()->json([
                'status' => 'success',
                'access_token' => $accessToken,
                'user' => $user,
            ], 200);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid Username or Password',
            ], 401);
        }
    }

    /**
     * Register
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'username' => 'required|string|unique:users',
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|string',
        ]);

        if (!$validated) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation error',
                'error' => $validated,
            ], 401);
        }

        try {
            $input = $request->all();
            $input['password'] = bcrypt($input['password']);
            $user = User::create($input);
            $accessToken = $user->createToken('appToken')->accessToken;

            return response()->json([
                'status' => 'success',
                'access_token' => $accessToken,
                'user' => $user
            ], 201);

        } catch (Exception $e) {
            $response = [
                'status' => 'error',
                'message' => $e
            ];
            return response()->json($response);
        }
    }

    /**
     * Logout
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        if (Auth::user()) {
            $user = $request->user()->token();
            $user->revoke();
            return response()->json([
                'status' => 'success',
                'message' => 'Logout successfully',
            ], 200);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Unable to Logout',
            ], 400);
        }
    }

    /**
     * User
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function user()
    {
        return response()->json([
            'status' => 'success',
            'data' => Auth::user(),
        ], 200);
    }
}
