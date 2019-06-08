<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterAuthRequest;
use App\User;
use Illuminate\Http\Request;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class ApiController extends Controller
{
    public $loginAfterSignUp = true;

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'id_number' => 'required|integer|min:8|unique:users',
            'role' => 'required|integer|max:1',
            'tel' => 'required|string|min:10|max:10|unique:users',
            'job_number' => 'required|integer|min:5|unique:users',
            'password' => 'required|string|min:4',
        ]);
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->id_number = $request->id_number;
        $user->role = $request->role;
        $user->tel = $request->tel;
        $user->job_number = $request->job_number;
        $user->password = bcrypt($request->password);
        $user->save();

        $name = $request->name;
        if ($this->loginAfterSignUp) {
            return $this->login($request);
        }

        return response()->json([
            'success' => true,

            'data' => $user
        ], 200);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required',
            
            'password' => 'required',
        ]);
        $email = $request->email;
        $input = $request->only('email','tel', 'password');
        $jwt_token = null;

        if (!$jwt_token = JWTAuth::attempt($input)) {
            return response()->json([
                'success' => false,
                
                'message' => 'Invalid Email or Password',
            ], 401);
        }

        return response()->json([
            'success' => true,
            'welcome' => $email,
            'token' => $jwt_token,
        ]);
    }

    public function logout(Request $request)
    {
        $this->validate($request, [
            'token' => 'required'
        ]);

        try {
            JWTAuth::invalidate($request->token);

            return response()->json([
                'success' => true,
                'message' => 'User logged out successfully'
            ]);
        } catch (JWTException $exception) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, the user cannot be logged out'
            ], 500);
        }
    }

    public function getAuthUser(Request $request)
    {
        $this->validate($request, [
            'token' => 'required'
        ]);

        $user = JWTAuth::authenticate($request->token);

        return response()->json([
            'success' => true,
            'token' => "valid",
            'user' => $user
        ], 200);
    }
}