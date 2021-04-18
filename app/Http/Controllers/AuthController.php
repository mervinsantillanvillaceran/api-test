<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Jobs\SendEmailUserPinJob;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        if (!$token = auth('api')->attempt($validator->validated())) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->createNewToken($token);
    }

    protected function createNewToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 10080,
            'user' => auth('api')->user()
        ]);
    }

    public function verifyToken(Request $request)
    {
        $token = $request->get('invitation_token');
        $user = User::where('invitation_token', $token)->get();

        return response()->json($user);
    }

    public function save(Request $request, $id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json('User not found.', 422);
        }

        $validator = Validator::make($request->all(), [
            'user_name' => 'required|min:4|max:20',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $pin = mt_rand(100000, 999999);

        $user->user_name = $validator->validated()['user_name'];
        $user->password = Hash::make($validator->validated()['password']);
        $user->registered_at = date('Y-m-d H:i:s');
        $user->pin = $pin;
        $user->save();

        dispatch(new SendEmailUserPinJob([
            'email' => $user->email,
            'pin' => $pin
        ]));

        if (!$token = auth('api')->attempt([
            'email' => $user->email,
            'password' => $validator->validated()['password']
        ])) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->createNewToken($token);
    }
}
